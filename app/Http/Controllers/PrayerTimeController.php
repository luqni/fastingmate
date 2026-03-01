<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PrayerTimeService;

class PrayerTimeController extends Controller
{
    protected $prayerTimeService;

    public function __construct(PrayerTimeService $prayerTimeService)
    {
        $this->prayerTimeService = $prayerTimeService;
    }

    /**
     * Get today's prayer times for the authenticated user
     */
    public function getTimes(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            $city = $user->prayer_city;
            $country = $user->prayer_country;
            $method = $user->prayer_method ?? 2;
        } else {
            // Default location for guests (Jakarta, Indonesia)
            $city = 'Jakarta';
            $country = 'Indonesia';
            $method = 2;
        }

        if (!$city || !$country) {
            return response()->json([
                'error' => 'location_not_set',
                'message' => 'Please set your location in settings first',
            ], 404);
        }

        $data = $this->prayerTimeService->getTodayPrayerTimes($city, $country, $method);

        if (!$data) {
            return response()->json([
                'error' => 'api_error',
                'message' => 'Failed to fetch prayer times',
            ], 500);
        }

        // Get next prayer info
        $nextPrayer = $this->prayerTimeService->getNextPrayer($data['timings']);

        // Get countdown state (Sahur or Iftar)
        $countdownState = $this->prayerTimeService->getCountdownState($data['timings']);

        return response()->json([
            'timings' => $data['timings'],
            'date' => $data['date'],
            'location' => [
                'city' => $city,
                'country' => $country,
            ],
            'next_prayer' => $nextPrayer,
            'countdown' => $countdownState,
        ]);
    }

    /**
     * Update user's prayer location settings
     */
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'prayer_city' => 'required|string|max:255',
            'prayer_country' => 'required|string|max:255',
            'prayer_method' => 'required|integer|min:0|max:15',
        ]);

        $request->user()->update($validated);

        return response()->json([
            'message' => 'Location settings updated successfully',
        ]);
    }

    /**
     * Get monthly prayer schedule
     */
    public function getMonthlySchedule(Request $request)
    {
        $user = $request->user();
        
        $city = $user->prayer_city;
        $country = $user->prayer_country;
        $method = $user->prayer_method ?? 2;

        if (!$city || !$country) {
            return response()->json([
                'error' => 'location_not_set',
                'message' => 'Please set your location in settings first',
            ], 404);
        }

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $schedule = $this->prayerTimeService->getMonthlySchedule($city, $country, $month, $year, $method);

        if (!$schedule) {
            return response()->json([
                'error' => 'api_error',
                'message' => 'Failed to fetch monthly schedule',
            ], 500);
        }

        return response()->json([
            'schedule' => $schedule,
            'month' => $month,
            'year' => $year,
            'location' => [
                'city' => $city,
                'country' => $country,
            ],
        ]);
    }

    /**
     * Search cities (Indonesia only for now)
     */
    public function searchCities(Request $request)
    {
        $query = $request->input('q');
        if (!$query || strlen($query) < 3) {
            return response()->json([]);
        }

        $cities = $this->prayerTimeService->searchIndonesianCities($query);
        return response()->json($cities);
    }

    /**
     * Display Ramadhan 1447 H Poster
     */
    public function ramadhanPoster(Request $request)
    {
        $settings = \App\Models\Setting::where('key', 'ramadhan_schedule_visible')->first();
        // Default to TRUE if setting is missing, or use the setting value
        $isVisible = $settings ? filter_var($settings->value, FILTER_VALIDATE_BOOLEAN) : true; 

        // If explicitly hidden and user is not admin
        if (!$isVisible && !$request->user()?->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Jadwal Ramadhan belum tersedia.');
        }

        $user = $request->user();

        // Create session for guest if city is provided in request
        if (!$user && $request->filled('city')) {
             session([
                 'guest_prayer_city' => $request->input('city'),
                 'guest_prayer_country' => $request->input('country', 'Indonesia'),
             ]); 
        }

        // Priority for Guests: Request Input > Session > Default
        // Priority for Users: Request Input > User Settings
        if ($user) {
            $city = $request->input('city') ?? $user->prayer_city;
            $country = $request->input('country') ?? $user->prayer_country;
        } else {
             $city = $request->input('city') ?? session('guest_prayer_city', 'Jakarta');
             $country = $request->input('country') ?? session('guest_prayer_country', 'Indonesia');
        }

        $method = $user?->prayer_method ?? 2;

        // Fetch Ramadhan 1447 Data
        $schedule = $this->prayerTimeService->getRamadhanSchedule($city, $country, 1447, $method);

        return view('prayer-times.ramadhan-poster', compact('schedule', 'city', 'country'));
    }

    /**
     * Get Ramadhan Data for Dynamic Island (JSON)
     */
    public function getRamadhanData(Request $request)
    {
        $settings = \App\Models\Setting::where('key', 'ramadhan_schedule_visible')->first();
        if ($settings && !filter_var($settings->value, FILTER_VALIDATE_BOOLEAN)) {
             return response()->json(['active' => false]);
        }
        
        $user = $request->user();
        $city = $user?->prayer_city ?? 'Jakarta';
        $country = $user?->prayer_country ?? 'Indonesia';
        $method = $user?->prayer_method ?? 2;
        
        // Start Date: 19 Feb 2026 for Ramadhan 1447H
        $today = now();
        $startRamadhan = \Carbon\Carbon::create(2026, 2, 19)->startOfDay();
        
        if ($today->lt($startRamadhan)) {
             $diff = (int)ceil($today->floatDiffInDays($startRamadhan, false));
             
             return response()->json([
                 'active' => true,
                 'type' => 'countdown_to_ramadhan',
                 'days_left' => $diff,
                 'message' => "H-$diff Menuju Ramadhan"
             ]);
        }
        
        // If in Ramadhan
        // Get today's prayer times
        $times = $this->prayerTimeService->getTodayPrayerTimes($city, $country, $method);
        
        if (!$times) return response()->json(['active' => false]);
        
        $dayOfRamadhan = $startRamadhan->diffInDays($today->copy()->startOfDay()) + 1;
        
        // Ramadhan is usually 29 or 30 days
        if ($dayOfRamadhan > 30) {
            return response()->json(['active' => false]); // Ramadhan over
        }

        // Get countdown state
        $state = $this->prayerTimeService->getCountdownState($times['timings']);
        
        return response()->json([
            'active' => true,
            'type' => 'in_ramadhan',
            'day' => $dayOfRamadhan,
            'hijri_year' => '1447 H',
            'timings' => $times['timings'],
            'countdown' => $state
        ]);
    }
}

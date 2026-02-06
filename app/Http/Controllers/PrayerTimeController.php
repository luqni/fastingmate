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
        
        $city = $user->prayer_city;
        $country = $user->prayer_country;
        $method = $user->prayer_method ?? 2;

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
}

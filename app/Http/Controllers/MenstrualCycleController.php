<?php

namespace App\Http\Controllers;

use App\Models\MenstrualCycle;
use App\Models\FastingDebt;
use App\Helpers\HijriDate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenstrualCycleController extends Controller
{
    public function index()
    {
        $cycles = Auth::user()->menstrualCycles()->latest('start_date')->get();
        return view('menstrual-cycles.index', compact('cycles'));
    }

    public function store(Request $request)
    {
        // Manual validation with clear error messages (works better with SweetAlert)
        if (!$request->start_date) {
            return back()->with('error', 'Tanggal mulai harus diisi.');
        }
        
        try {
            $startDate = Carbon::parse($request->start_date);
        } catch (\Exception $e) {
            return back()->with('error', 'Format tanggal tidak valid.');
        }
        
        // Check if date is not in the future
        if ($startDate->isFuture()) {
            return back()->with('error', 'Tanggal mulai tidak boleh di masa depan.');
        }

        // Check if TODAY (current date) is in Ramadan
        // Users can only input menstrual cycles when we are currently in Ramadan month
        
        $today = Carbon::now();
        $todayHijri = HijriDate::gregorianToHijri($today->day, $today->month, $today->year);

        // Ramadan is month 9
        if ($todayHijri['month'] != 9) {
            return back()->with('error', 'Hanya bisa mencatat siklus haid saat sedang di bulan Ramadhan. Saat ini bulan Hijriyah: ' . $this->getHijriMonthName($todayHijri['month']) . '.');
        }

        // Check if there is an active cycle
        $activeCheck = Auth::user()->menstrualCycles()->whereNull('end_date')->exists();
        if ($activeCheck) {
            return back()->with('error', 'Anda masih memiliki siklus haid yang aktif.');
        }

        Auth::user()->menstrualCycles()->create([
            'start_date' => $request->start_date,
        ]);

        return redirect()->route('menstrual-cycles.index')->with('success', 'Haid dimulai.');
    }

    public function update(Request $request, MenstrualCycle $menstrualCycle)
    {
        if ($menstrualCycle->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ]);

        $menstrualCycle->update([
            'end_date' => $request->end_date
        ]);

        // Calculate missed days in Ramadan
        $startDate = Carbon::parse($menstrualCycle->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $ramadanDaysMissed = 0;
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $hijri = HijriDate::gregorianToHijri($date->day, $date->month, $date->year);
            // Ramadan is month 9
            if ($hijri['month'] == 9) {
                $ramadanDaysMissed++;
            }
        }

        if ($ramadanDaysMissed > 0) {
            $currentYear = now()->year;
            
            // Find or create FastingDebt for current year
            $debt = FastingDebt::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'year' => $currentYear
                ],
                [
                    'total_days' => 0,
                    'paid_days' => 0,
                    'is_paid_off' => false
                ]
            );

            // Add missed days
            $debt->increment('total_days', $ramadanDaysMissed);

            // Mark cycle as converted
            $menstrualCycle->update(['converted_to_debt' => true]);

            return back()->with('success', "Haid selesai. $ramadanDaysMissed hari hutang puasa Ramadhan telah ditambahkan otomatis.");
        } else {
             // If not in Ramadan, still mark as processed so we don't prompt to add debt
             $menstrualCycle->update(['converted_to_debt' => true]);
             return back()->with('success', 'Haid selesai. Tidak ada hari Ramadhan yang terlewat.');
        }
    }
    
    private function getHijriMonthName($monthNumber)
    {
        $months = [
            1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal', 4 => 'Rabiul Akhir',
            5 => 'Jumadil Awal', 6 => 'Jumadil Akhir', 7 => 'Rajab', 8 => 'Syaban',
            9 => 'Ramadhan', 10 => 'Syawal', 11 => 'Dzulkaidah', 12 => 'Dzulhijjah'
        ];
        return $months[$monthNumber] ?? 'Unknown';
    }
    
    public function destroy(MenstrualCycle $menstrualCycle)
    {
        if ($menstrualCycle->user_id !== Auth::id()) {
            abort(403);
        }

        $menstrualCycle->delete();

        return back()->with('success', 'Riwayat haid berhasil dihapus.');
    }
}

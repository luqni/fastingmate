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
        $request->validate([
            'start_date' => 'required|date|before_or_equal:today',
        ]);

        // Check if the start date is in Ramadan
        $startDate = Carbon::parse($request->start_date);
        $hijri = HijriDate::gregorianToHijri($startDate->day, $startDate->month, $startDate->year);
        
        // Ramadan is month 9
        if ($hijri['month'] != 9) {
            return back()->with('error', 'Hanya bisa mencatat siklus haid di bulan Ramadhan.');
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
    public function destroy(MenstrualCycle $menstrualCycle)
    {
        if ($menstrualCycle->user_id !== Auth::id()) {
            abort(403);
        }

        $menstrualCycle->delete();

        return back()->with('success', 'Riwayat haid berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SmartSchedule;
use App\Models\FastingPlan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalDebt = $user->fastingDebts()->sum('total_days');
        $totalPaid = $user->fastingDebts()->sum('paid_days');
        $remainingDebt = $totalDebt - $totalPaid;
        
        $progressPercentage = $totalDebt > 0 ? round(($totalPaid / $totalDebt) * 100) : 0;

        $nextFasting = SmartSchedule::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereDate('scheduled_date', '>=', Carbon::today())
            ->orderBy('scheduled_date')
            ->first();

        // Get schedules for the monthly calendar view
        $schedules = SmartSchedule::where('user_id', $user->id)
            ->whereMonth('scheduled_date', Carbon::now()->month)
            ->whereYear('scheduled_date', Carbon::now()->year)
            ->orderBy('scheduled_date', 'asc')
            ->get()
            ->map(function ($item) {
                $item->type = 'debt';
                return $item;
            });

        // Get manual fasting plans
        $plans = FastingPlan::where('user_id', $user->id)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->get()
            ->map(function ($item) {
                $item->type = 'plan';
                $item->scheduled_date = Carbon::parse($item->date); // Map date to scheduled_date for consistency
                return $item;
            });

        // Merge and sort
        $schedules = $schedules->concat($plans)->sortBy('scheduled_date');

        $activeCycle = $user->menstrualCycles()
            ->whereNull('end_date')
            ->latest('start_date')
            ->first();

        $nextRamadan = $this->getNextRamadanDate();
        $daysToRamadan = ceil(Carbon::now()->floatDiffInDays($nextRamadan['date'], false));

        $tadabbur = app(\App\Services\TadabburService::class)->getTodayTadabbur($user);

        $todayTadabbur = app(\App\Services\TadabburService::class)->getTodayTadabbur($user);

        return view('dashboard', compact('remainingDebt', 'progressPercentage', 'nextFasting', 'schedules', 'activeCycle', 'nextRamadan', 'daysToRamadan', 'todayTadabbur'));
    }

    private function getNextRamadanDate()
    {
        $now = Carbon::now();
        $hijriNow = \App\Helpers\HijriDate::gregorianToHijri($now->day, $now->month, $now->year);
        
        // Determine target Hijri year for next Ramadan
        // If current month is before Ramadan (9), use current hijri year
        // If current month is Ramadan (9) or later, use next hijri year
        $targetHijriYear = $hijriNow['month'] < 9 ? $hijriNow['year'] : $hijriNow['year'] + 1;
        
        // Rough estimation: 1 Hijri year = 354 days
        // We want Month 9, Day 1
        // Calculate months difference roughly
        $monthDiff = (9 - $hijriNow['month']);
        if ($monthDiff <= 0) $monthDiff += 12; // Wrap around if target is next year
        
        $daysToAdd = ($monthDiff * 29.5) + (1 - $hijriNow['day']);
        $estimatedDate = $now->copy()->addDays($daysToAdd);
        
        // Refine search - scan around estimated date
        // Search range: -5 to +5 days from estimate
        $found = null;
        for ($i = -5; $i <= 5; $i++) {
            $checkDate = $estimatedDate->copy()->addDays($i);
            $h = \App\Helpers\HijriDate::gregorianToHijri($checkDate->day, $checkDate->month, $checkDate->year);
            
            if ($h['month'] == 9 && $h['day'] == 1 && $h['year'] == $targetHijriYear) {
                $found = $checkDate;
                break;
            }
        }
        
        // Fallback if exact day 1 not found (should rarely happen with broad range, but logic safety)
        // Try larger range if needed, or just pick the first date that is month 9
        if (!$found) {
             for ($i = -10; $i <= 10; $i++) {
                $checkDate = $estimatedDate->copy()->addDays($i);
                $h = \App\Helpers\HijriDate::gregorianToHijri($checkDate->day, $checkDate->month, $checkDate->year);
                if ($h['month'] == 9 && $h['year'] == $targetHijriYear) {
                     // Found a ramadan day, backtrack to find day 1
                     $found = $checkDate->subDays($h['day'] - 1);
                     break;
                }
            }
        }

        return [
            'date' => $found ?? $estimatedDate, // Fallback to estimate if simple search fails
            'hijri_year' => $targetHijriYear
        ];
    }
}

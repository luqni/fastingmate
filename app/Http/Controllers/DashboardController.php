<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SmartSchedule;
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
            ->get();

        $activeCycle = $user->menstrualCycles()
            ->whereNull('end_date')
            ->latest('start_date')
            ->first();

        return view('dashboard', compact('remainingDebt', 'progressPercentage', 'nextFasting', 'schedules', 'activeCycle'));
    }
}

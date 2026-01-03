<?php

namespace App\Http\Controllers;

use App\Helpers\HijriDate;
use App\Models\FastingPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FastingPlanController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startOfMonth->daysInMonth;
        
        $calendar = [];
        $padding = $startOfMonth->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
        
        // Add padding for start of month
        for ($i = 0; $i < $padding; $i++) {
            $calendar[] = null;
        }

        $userPlans = Auth::user()->fastingPlans()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(function($item) {
                return $item->date->format('Y-m-d');
            });

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $hijri = HijriDate::gregorianToHijri($day, $month, $year);
            $sunnahType = HijriDate::getSunnahType($date);
            
            $calendar[] = [
                'date' => $date,
                'hijri' => $hijri,
                'sunnah_type' => $sunnahType,
                'plan' => $userPlans[$date->format('Y-m-d')] ?? null,
            ];
        }

        return view('fasting-plans.index', compact('calendar', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'nullable|string',
        ]);

        // Toggle logic: If exists, delete. If not, create.
        // Or specific create if needed.
        // For simple UI, clicking a date might just "toggle plan".
        
        $plan = FastingPlan::updateOrCreate(
            ['user_id' => Auth::id(), 'date' => $request->date],
            ['type' => $request->type ?? 'other', 'status' => 'planned']
        );

        return back()->with('success', 'Rencana puasa disimpan.');
    }

    public function update(Request $request, FastingPlan $fastingPlan)
    {
        if ($fastingPlan->user_id !== Auth::id()) {
            abort(403);
        }
        
        $fastingPlan->update([
            'status' => $request->status // 'completed' or 'planned'
        ]);

        return back()->with('success', 'Status puasa diperbarui.');
    }

    public function destroy(FastingPlan $fastingPlan)
    {
        if ($fastingPlan->user_id !== Auth::id()) {
            abort(403);
        }

        $fastingPlan->delete();
        
        return back()->with('success', 'Rencana puasa dihapus.');
    }
}

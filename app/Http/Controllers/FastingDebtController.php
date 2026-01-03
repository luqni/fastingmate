<?php

namespace App\Http\Controllers;

use App\Models\FastingDebt;
use App\Services\FastingPlanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FastingDebtController extends Controller
{
    public function index()
    {
        $debts = Auth::user()->fastingDebts()->orderBy('year', 'desc')->get();
        
        // Calendar Data
        $currentMonth = Carbon::now();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();
        
        $schedules = Auth::user()->smartSchedules()
            ->whereBetween('scheduled_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(function($item) {
                return $item->scheduled_date->format('Y-m-d');
            });

        return view('fasting-debts.index', compact('debts', 'schedules', 'currentMonth', 'startOfMonth', 'endOfMonth'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'total_days' => 'required|integer|min:1',
            'target_finish_date' => 'nullable|date|after:today',
        ]);

        Auth::user()->fastingDebts()->updateOrCreate(
            ['year' => $validated['year']],
            [
                'total_days' => $validated['total_days'],
                'target_finish_date' => $validated['target_finish_date']
            ]
        );

        return redirect()->route('fasting-debts.index')->with('success', 'Hutang puasa berhasil disimpan.');
    }

    public function update(Request $request, FastingDebt $fastingDebt)
    {
        $this->authorize('update', $fastingDebt); // Need to Policy? Or just check ID.

        $validated = $request->validate([
            'paid_days' => 'required|integer|min:0|max:' . $fastingDebt->total_days,
            'is_paid_off' => 'boolean',
        ]);

        $fastingDebt->update($validated);

        return back()->with('success', 'Progress berhasil diupdate.');
    }
    
    public function generateSchedule(Request $request, FastingDebt $fastingDebt)
    {
        if ($fastingDebt->user_id !== Auth::id()) {
            abort(403);
        }

        // If target date is provided, use SmartPlannerService
        if ($request->has('target_date')) {
            $targetDate = Carbon::parse($request->target_date);
            $service = new \App\Services\SmartPlannerService(); // Direct instantiation or inject
            $service->generateScheduleByTarget(Auth::user(), $fastingDebt, $targetDate);
        } else {
             // Fallback to old simple Mon/Thu generator (or inject FastingPlanService)
             $service = new \App\Services\FastingPlanService();
             $service->generateSchedule(Auth::user(), $fastingDebt);
        }

        return back()->with('success', 'Jadwal puasa berhasil dibuat.');
    }

    public function updateProgress(Request $request, FastingDebt $fastingDebt)
    {
        if ($fastingDebt->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'paid_days_add' => 'required|integer|min:1',
        ]);

        $newPaidTotal = $fastingDebt->paid_days + $validated['paid_days_add'];

        if ($newPaidTotal > $fastingDebt->total_days) {
            return back()->with('error', 'Jumlah hari yang dibayar melebihi total hutang.');
        }

        $fastingDebt->update([
            'paid_days' => $newPaidTotal,
            'is_paid_off' => $newPaidTotal >= $fastingDebt->total_days
        ]);

        $fastingDebt->repayments()->create([
            'paid_days' => $validated['paid_days_add'],
            'repayment_date' => now(),
            'description' => 'Bayar Manual',
        ]);

        return back()->with('success', 'Progress pembayaran hutang berhasil dicatat.');
    }

    public function history(FastingDebt $fastingDebt)
    {
        if ($fastingDebt->user_id !== Auth::id()) {
            abort(403);
        }

        $repayments = $fastingDebt->repayments;

        return view('fasting-debts.history', compact('fastingDebt', 'repayments'));
    }

    public function destroy(FastingDebt $fastingDebt)
    {
        if ($fastingDebt->user_id !== Auth::id()) {
            abort(403);
        }

        $fastingDebt->delete();

        return redirect()->route('fasting-debts.index')->with('success', 'Catatan hutang puasa berhasil dihapus.');
    }
}

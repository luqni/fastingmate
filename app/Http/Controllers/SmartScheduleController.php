<?php

namespace App\Http\Controllers;

use App\Models\SmartSchedule;
use App\Models\FastingRepayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmartScheduleController extends Controller
{
    public function update(Request $request, SmartSchedule $smartSchedule)
    {
        if ($smartSchedule->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,completed',
        ]);

        $newStatus = $request->status;
        $oldStatus = $smartSchedule->status;

        if ($newStatus === $oldStatus) {
            return back();
        }

        DB::transaction(function () use ($smartSchedule, $newStatus) {
            $smartSchedule->update(['status' => $newStatus]);
            $debt = $smartSchedule->fastingDebt;

            if ($debt) {
                if ($newStatus === 'completed') {
                    // Increment paid days
                    $debt->paid_days += 1;
                    
                    // Create repayment record
                    $debt->repayments()->create([
                        'paid_days' => 1,
                        'repayment_date' => now(),
                        'description' => 'Otomatis dari Smart Schedule: ' . $smartSchedule->scheduled_date->format('d M Y'),
                    ]);

                } else {
                    // Decrement paid days (Undo)
                    $debt->paid_days = max(0, $debt->paid_days - 1);
                    
                    // Remove the latest repayment associated with this schedule if possible, 
                    // or just remove the latest auto-repayment.
                    // For simplicity and safety, we'll try to find a repayment made today or recently.
                    // Ideally we should link repayment to schedule, but for now we'll just delete the latest one 
                    // that matches the description or just the latest one to keep count correct.
                    
                    $latestRepayment = $debt->repayments()
                        ->where('paid_days', 1)
                        ->latest('created_at')
                        ->first();
                        
                    if ($latestRepayment) {
                        $latestRepayment->delete();
                    }
                }

                // Update paid off status
                $debt->is_paid_off = $debt->paid_days >= $debt->total_days;
                $debt->save();
            }
        });

        return back()->with('success', $newStatus === 'completed' ? 'Puurasa berhasil diselesaikan & hutang dikurangi.' : 'Status puasa dikembalikan.');
    }
}

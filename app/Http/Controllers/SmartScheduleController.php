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
                    // Prevent negative debt (overpayment)
                    if ($debt->paid_days < $debt->total_days) {
                        // Increment paid days
                        $debt->paid_days += 1;
                        
                        // Create repayment record linked to this schedule
                        $debt->repayments()->create([
                            'smart_schedule_id' => $smartSchedule->id,
                            'paid_days' => 1,
                            'repayment_date' => now(),
                            'description' => 'Otomatis dari Smart Schedule: ' . $smartSchedule->scheduled_date->format('d M Y'),
                        ]);
                    }

                } else {
                    // Decrement paid days (Undo)
                    // Only delete the repayment that belongs to THIS specific schedule
                    $repayment = $debt->repayments()
                        ->where('smart_schedule_id', $smartSchedule->id)
                        ->first();
                        
                    if ($repayment) {
                        // Safely decrement, ensuring it doesn't go below 0
                        $debt->paid_days = max(0, $debt->paid_days - $repayment->paid_days);
                        $repayment->delete();
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

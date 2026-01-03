<?php

namespace App\Observers;

use App\Models\MenstrualCycle;
use App\Models\FastingDebt;
use Carbon\Carbon;

class MenstrualCycleObserver
{
    /**
     * Handle the MenstrualCycle "created" event.
     */
    public function created(MenstrualCycle $menstrualCycle): void
    {
        $this->processCycle($menstrualCycle);
    }

    /**
     * Handle the MenstrualCycle "updated" event.
     */
    public function updated(MenstrualCycle $menstrualCycle): void
    {
        $this->processCycle($menstrualCycle);
    }

    protected function processCycle(MenstrualCycle $menstrualCycle): void
    {
        if ($menstrualCycle->end_date && ! $menstrualCycle->converted_to_debt) {
            $days = $menstrualCycle->start_date->diffInDays($menstrualCycle->end_date) + 1;
            
            // Find or create debt for the year of the start date
            $year = $menstrualCycle->start_date->year;
            
            $debt = FastingDebt::firstOrCreate(
                ['user_id' => $menstrualCycle->user_id, 'year' => $year],
                ['total_days' => 0]
            );
            
            $debt->increment('total_days', $days);
            
            $menstrualCycle->converted_to_debt = true;
            $menstrualCycle->saveQuietly();
        }
    }

    /**
     * Handle the MenstrualCycle "deleted" event.
     */
    public function deleted(MenstrualCycle $menstrualCycle): void
    {
        if ($menstrualCycle->converted_to_debt && $menstrualCycle->end_date) {
            $days = $menstrualCycle->start_date->diffInDays($menstrualCycle->end_date) + 1;
            
            $year = $menstrualCycle->start_date->year;
            
            $debt = FastingDebt::where('user_id', $menstrualCycle->user_id)
                ->where('year', $year)
                ->first();
                
            if ($debt) {
                $debt->decrement('total_days', $days);
                
                // Optional: Recalculate is_paid_off just in case
                if ($debt->paid_days >= $debt->total_days) {
                    $debt->update(['is_paid_off' => true]);
                }
            }
        }
    }
}

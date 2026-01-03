<?php

namespace App\Services;

use App\Models\FastingDebt;
use App\Models\SmartSchedule;
use App\Models\User;
use Carbon\Carbon;

class FastingPlanService
{
    public function generateSchedule(User $user, FastingDebt $debt)
    {
        // Clear existing pending schedules for this debt
        SmartSchedule::where('fasting_debt_id', $debt->id)
            ->where('status', 'pending')
            ->delete();

        $remainingDays = $debt->total_days - $debt->paid_days;
        if ($remainingDays <= 0) return;

        $targetDate = $debt->target_finish_date ?? Carbon::now()->addYear(); // Default to next year if not set
        $startDate = Carbon::today();

        $scheduledCount = 0;
        $currentDate = $startDate->copy()->addDay(); // Start from tomorrow

        while ($scheduledCount < $remainingDays && $currentDate->lte($targetDate)) {
            // Prioritize Mondays (1) and Thursdays (4)
            if ($currentDate->dayOfWeekIso === 1 || $currentDate->dayOfWeekIso === 4) {
                SmartSchedule::create([
                    'user_id' => $user->id,
                    'fasting_debt_id' => $debt->id,
                    'scheduled_date' => $currentDate->format('Y-m-d'),
                    'status' => 'pending',
                ]);
                $scheduledCount++;
            }
            $currentDate->addDay();
        }
    }
}

<?php

namespace App\Services;

use App\Models\FastingDebt;
use App\Models\SmartSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SmartPlannerService
{
    /**
     * Generate schedule based on target date.
     * Prioritizes Mondays and Thursdays.
     * If not enough Mondays/Thursdays before target, it adds other days (weekend/weekdays).
     */
    public function generateScheduleByTarget(User $user, FastingDebt $fastingDebt, Carbon $targetDate)
    {
        // Clear existing pending schedules
        SmartSchedule::where('fasting_debt_id', $fastingDebt->id)
            ->where('status', 'pending')
            ->delete();

        $remainingDays = $fastingDebt->total_days - $fastingDebt->paid_days;
        if ($remainingDays <= 0) return;

        $scheduledCount = 0;
        $currentDate = Carbon::now()->addDay(); // Start tomorrow

        // Strategy 1: Try filling with Mon/Thu only
        $potentialDates = new Collection();
        $iterDate = $currentDate->copy();
        
        while ($iterDate->lte($targetDate)) {
            // Monday (1) or Thursday (4)
            if ($iterDate->dayOfWeekIso === 1 || $iterDate->dayOfWeekIso === 4) {
                // Check conflicts
                if (!$this->hasScheduleOn($user, $iterDate)) {
                     $potentialDates->push($iterDate->copy());
                }
            }
            $iterDate->addDay();
        }

        // If we have more potential dates than needed, take the first N
        if ($potentialDates->count() >= $remainingDays) {
            foreach ($potentialDates->take($remainingDays) as $date) {
                $this->createSchedule($user, $fastingDebt, $date);
            }
            return;
        }

        // Strategy 2: Not enough Mon/Thu. Fill all Mon/Thu, then add weekends/other days.
        // First, add all available Mon/Thu
        foreach ($potentialDates as $date) {
            $this->createSchedule($user, $fastingDebt, $date);
            $scheduledCount++;
        }

        // Then fill remaining with other days, avoiding existing schedules
        $iterDate = $currentDate->copy();
        while ($scheduledCount < $remainingDays && $iterDate->lte($targetDate)) {
            // Skip if already scheduled (Mon/Thu we just added) or external conflict
            if (!$this->hasScheduleOn($user, $iterDate)) {
                 $this->createSchedule($user, $fastingDebt, $iterDate);
                 $scheduledCount++;
            }
            $iterDate->addDay();
        }
    }

    private function hasScheduleOn(User $user, Carbon $date)
    {
        return SmartSchedule::where('user_id', $user->id)
            ->whereDate('scheduled_date', $date)
            ->exists();
    }

    private function createSchedule(User $user, FastingDebt $debt, Carbon $date)
    {
        SmartSchedule::create([
            'user_id' => $user->id,
            'fasting_debt_id' => $debt->id,
            'scheduled_date' => $date,
            'status' => 'pending',
        ]);
    }
}

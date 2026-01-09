<?php

namespace App\Services;

use App\Models\DailyTadabbur;
use App\Models\QuranSource;
use App\Models\User;
use Carbon\Carbon;

class TadabburService
{
    /**
     * Get or create the unique daily tadabbur for the user for today.
     *
     * @param User $user
     * @return DailyTadabbur|null
     */
    public function getTodayTadabbur(User $user): ?DailyTadabbur
    {
        $today = Carbon::today();

        // 1. Try to find existing record for today
        $existing = DailyTadabbur::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->with('quranSource')
            ->first();

        if ($existing) {
            return $existing;
        }

        // 2. If not found, generate a new one
        
        // Pick a random Quran source
        // Ideally, we could filter out sources already seen by the user recently, 
        // but for now, pure random from available sources.
        $source = QuranSource::inRandomOrder()->first();

        if (!$source) {
            return null; // Should not happen if seeded
        }

        // Create the daily record
        return DailyTadabbur::create([
            'user_id' => $user->id,
            'quran_source_id' => $source->id,
            'date' => $today,
            'status' => 'pending',
            'reflection' => null,
        ]);
    }
}

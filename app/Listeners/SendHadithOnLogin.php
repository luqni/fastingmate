<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\HadithService;
use App\Notifications\DailyHadithNotification;
use Carbon\Carbon;

class SendHadithOnLogin
{
    protected $hadithService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(HadithService $hadithService)
    {
        $this->hadithService = $hadithService;
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $today = Carbon::today();

        // Check if user already received hadith today
        // We check the notifications table for a notification of this type created today
        $alreadySent = $user->notifications()
            ->where('type', DailyHadithNotification::class)
            ->whereDate('created_at', $today)
            ->exists();

        if (!$alreadySent) {
            $hadith = $this->hadithService->getRandomHadith();
            $user->notify(new DailyHadithNotification($hadith));
        }
    }
}

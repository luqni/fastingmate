<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Helpers\HijriDate;
use App\Models\User;
use App\Notifications\FastingReminder;
use Carbon\Carbon;

class SendFastingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fasting:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications for tomorrow\'s sunnah fasting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow();
        $sunnahType = HijriDate::getSunnahType($tomorrow);
        
        // Skip if forbidden or no special sunnah (or maybe remind for Mondays/Thursdays)
        if (!$sunnahType || $sunnahType === 'haram') {
            $this->info("No sunnah fasting for tomorrow ({$tomorrow->format('Y-m-d')}).");
            return;
        }

        $message = "Besok adalah puasa " . ucfirst(str_replace('_', ' ', $sunnahType)) . ". Jangan lupa niat dan sahur ya!";
        
        // Customize message for specific types
        if ($sunnahType === 'ayyamul_bidh') {
             $message = "Besok adalah Ayyamul Bidh. Yuk puasa!";
        } elseif ($sunnahType === 'ramadhan') {
             $message = "Besok adalah puasa Ramadhan. Semangat!";
        }

        $users = User::has('pushSubscriptions')->get();
        // Or filter by users who enabled this setting if we had a settings column

        $count = 0;
        foreach ($users as $user) {
            $user->notify(new FastingReminder($message));
            $count++;
        }

        $this->info("Sent {$count} fasting reminders for {$sunnahType}.");
    }
}

<?php

namespace App\Console\Commands;

use App\Models\DailyTadabbur;
use App\Models\QuranSource;
use App\Models\User;
use App\Notifications\DailyTadabburNotification;
use App\Notifications\FastingReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailyTadabbur extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tadabbur:notify {--reminder : Send reminder only to those who have not inputted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily tadabbur notification or reminder to input reflection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isReminder = $this->option('reminder');
        $today = Carbon::today()->format('Y-m-d');

        if ($isReminder) {
            $this->sendInputReminder($today);
        } else {
            $this->sendDailyAyah();
        }
    }

    protected function sendDailyAyah()
    {
        $this->info('Sending daily tadabbur ayah...');

        $tadabburService = app(\App\Services\TadabburService::class);

        // Notify all users
        User::chunk(100, function ($users) use ($tadabburService) {
            foreach ($users as $user) {
                try {
                    // Get or create the personalized tadabbur for today
                    $dailyTadabbur = $tadabburService->getTodayTadabbur($user);
                    
                    if ($dailyTadabbur && $dailyTadabbur->quranSource) {
                        $user->notify(new DailyTadabburNotification($dailyTadabbur->quranSource));
                    } else {
                        Log::warning("Could not generate daily tadabbur for user {$user->id}");
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to send tadabbur to user {$user->id}: " . $e->getMessage());
                }
            }
        });

        $this->info('Daily tadabbur sent.');
    }

    protected function sendInputReminder($date)
    {
        $this->info("Sending tadabbur input reminder for {$date}...");

        // Find users who have NOT created a DailyTadabbur entry for today
        User::whereDoesntHave('dailyTadabburs', function ($query) use ($date) {
            $query->whereDate('date', $date);
        })->chunk(100, function ($users) {
            foreach ($users as $user) {
                try {
                    $user->notify(new \App\Notifications\TadabburReminder());
                } catch (\Exception $e) {
                    Log::error("Failed to send tadabbur reminder to user {$user->id}: " . $e->getMessage());
                }
            }
        });

        $this->info('Tadabbur input reminders sent.');
    }
}

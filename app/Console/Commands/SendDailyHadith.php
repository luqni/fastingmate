<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\HadithService;
use App\Notifications\DailyHadithNotification;

class SendDailyHadith extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:hadith';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily random Hadith to all users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(HadithService $hadithService)
    {
        $this->info('Fetching random Hadith...');
        
        $hadith = $hadithService->getRandomHadith();
        $this->line("Selected: " . $hadith['source']);

        $users = User::all();
        $this->info("Sending to {$users->count()} users...");

        foreach ($users as $user) {
            $user->notify(new DailyHadithNotification($hadith));
            $this->line("Sent to: {$user->email}");
        }

        $this->info('Daily Hadith sent successfully!');
        return 0;
    }
}

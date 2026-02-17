<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\GeneralAnnouncement;

class SendAnnouncement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-announcement {message} {--title=Pengumuman} {--url=/}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a general announcement via push notification to all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $message = $this->argument('message');
        $title = $this->option('title');
        $url = $this->option('url');

        $this->info("Sending announcement: '{$title} - {$message}' to all users...");

        $count = 0;
        
        // Chunk users to avoid memory issues
        User::chunk(100, function ($users) use ($title, $message, $url, &$count) {
            foreach ($users as $user) {
                try {
                    $user->notify(new GeneralAnnouncement($title, $message, $url));
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to notify user {$user->id}: " . $e->getMessage());
                }
            }
            $this->info("Processed {$count} users...");
        });

        $this->info("Announcement sent to {$count} users successfully.");
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\PrayerTimeAlert;
use App\Services\PrayerTimeService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendPrayerNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prayers:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule daily prayer notifications for all users based on their location';

    /**
     * Execute the console command.
     */
    public function handle(PrayerTimeService $service)
    {
        $this->info('Starting prayer notification scheduling...');

        $today = Carbon::today();
        $sunnahType = \App\Helpers\HijriDate::getSunnahType($today);
        $isFastingDay = $sunnahType && $sunnahType !== 'haram';

        User::whereNotNull('prayer_city')
            ->whereNotNull('prayer_country')
            ->chunk(100, function ($users) use ($service, $isFastingDay) {
                foreach ($users as $user) {
                    try {
                        $this->scheduleForUser($user, $service, $isFastingDay);
                    } catch (\Exception $e) {
                        $this->error("Failed to schedule for user {$user->id}: " . $e->getMessage());
                    }
                }
            });

        $this->info('Prayer notifications scheduling completed.');
    }

    protected function scheduleForUser(User $user, PrayerTimeService $service, $isFastingDay = false)
    {
        $times = $service->getTodayPrayerTimes($user->prayer_city, $user->prayer_country, $user->prayer_method ?? 2);
        
        if (!$times || empty($times['timings'])) {
            return;
        }

        $timings = $times['timings'];
        // Generic prayers to notify at exact time
        $prayers = ['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isya']; // Note: Service might return 'Isha' or 'Isya' depending on formatter?
        // Service formatKemenagResponse returns 'Isha' key. Aladhan returns 'Isha'.
        // Let's use standard keys: Fajr, Dhuhr, Asr, Maghrib, Isha.
        
        $prayerKeys = ['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'];

        foreach ($prayerKeys as $prayer) {
            if (!isset($timings[$prayer])) continue;

            $timeStr = $timings[$prayer];
            // Format H:i
            try {
                $prayerTime = Carbon::createFromFormat('H:i', $timeStr);
            } catch (\Exception $e) {
                continue;
            }

            if ($prayerTime->isPast()) continue;

            // Determine type and custom name
            $type = 'prayer';
            $displayName = $prayer;

            if ($prayer === 'Maghrib') {
                $type = $isFastingDay ? 'iftar' : 'prayer';
                $displayName = 'Maghrib';
            } elseif ($prayer === 'Fajr') {
                $type = 'prayer'; // Subuh
                $displayName = 'Subuh';
            } elseif ($prayer === 'Isha') {
                $displayName = 'Isya';
            }

            // Dispatch notification
            $user->notify((new PrayerTimeAlert($displayName, $timeStr, $type))->delay($prayerTime));
            //$this->line("Scheduled $displayName for User {$user->id} at $timeStr");
        }

        // Schedule SAHUR (1 hour before Imsak)
        if ($isFastingDay && isset($timings['Imsak'])) {
            $imsakTime = Carbon::createFromFormat('H:i', $timings['Imsak']);
            $sahurTime = $imsakTime->copy()->subMinutes(60);

            if ($sahurTime->isFuture()) {
                $user->notify((new PrayerTimeAlert('Sahur', $timings['Imsak'], 'sahur'))->delay($sahurTime));
                //$this->line("Scheduled Sahur for User {$user->id} at " . $sahurTime->format('H:i'));
            }
        }
    }
}

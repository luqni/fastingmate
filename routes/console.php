<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SendFastingReminders;
use App\Console\Commands\SendDailyHadith;

Schedule::command('fasting:reminders')->dailyAt('20:00')->timezone('Asia/Jakarta');
Schedule::command('daily:hadith')->dailyAt('05:00')->timezone('Asia/Jakarta');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

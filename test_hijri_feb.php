<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

for ($i = 16; $i <= 20; $i++) {
    $date = Carbon\Carbon::create(2026, 2, $i);
    $hijri = \App\Helpers\HijriDate::gregorianToHijri($date->day, $date->month, $date->year);
    echo $date->format('Y-m-d') . " : " . $hijri['day'] . " " . \App\Helpers\HijriDate::getMonthName($hijri['month']) . " " . $hijri['year'] . "\n";
}

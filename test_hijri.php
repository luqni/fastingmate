<?php

require __DIR__.'/vendor/autoload.php';

use App\Helpers\HijriDate;
use Carbon\Carbon;

// Test Case 1: Known Ramadan Date (10 March 2024 was ~1 Ramadan 1445)
$date1 = Carbon::create(2024, 3, 11); // 1 Ramadan 1445 is around 11/12 March
$hijri1 = HijriDate::gregorianToHijri($date1->day, $date1->month, $date1->year);
echo "11 March 2024: Month " . $hijri1['month'] . "\n";

// Test Case 2: Today (Jan 2026) -> Should NOT be month 9
$date2 = Carbon::now();
$hijri2 = HijriDate::gregorianToHijri($date2->day, $date2->month, $date2->year);
echo "Today ({$date2->toDateString()}): Month " . $hijri2['month'] . "\n";

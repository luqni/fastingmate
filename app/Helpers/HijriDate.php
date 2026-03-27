<?php

namespace App\Helpers;

use Carbon\Carbon;

class HijriDate
{
    public static function format($date)
    {
        if (!$date) return '-';
        
        $date = Carbon::parse($date);
        
        $res = self::gregorianToHijri($date->day, $date->month, $date->year);
        
        $months = [
            1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal', 4 => 'Rabiul Akhir',
            5 => 'Jumadil Awal', 6 => 'Jumadil Akhir', 7 => 'Rajab', 8 => 'Syaban',
            9 => 'Ramadhan', 10 => 'Syawal', 11 => 'Dzulkaidah', 12 => 'Dzulhijjah'
        ];

        return $res['day'] . ' ' . $months[$res['month']] . ' ' . $res['year'];
    }

    public static function getMonthName($month)
    {
        $months = [
            1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal', 4 => 'Rabiul Akhir',
            5 => 'Jumadil Awal', 6 => 'Jumadil Akhir', 7 => 'Rajab', 8 => 'Syaban',
            9 => 'Ramadhan', 10 => 'Syawal', 11 => 'Dzulkaidah', 12 => 'Dzulhijjah'
        ];

        return $months[$month] ?? '';
    }

    public static function gregorianToHijri($d, $m, $y)
    {
        if (function_exists('gregoriantojd')) {
            $jd = gregoriantojd($m, $d, $y);
        } else {
            $jd = (int)(\Carbon\Carbon::create($y, $m, $d, 12, 0, 0)->getTimestamp() / 86400) + 2440588;
        }

        $l = $jd - 1948440 + 10632;
        $n = floor(($l - 1) / 10631);
        $l = $l - 10631 * $n + 354;
        $j = (floor((10985 - $l) / 5316)) * (floor((50 * $l) / 17719)) + (floor($l / 5670)) * (floor((43 * $l) / 15238));
        $l = $l - (floor((30 - $j) / 15)) * (floor((17719 * $j) / 50)) - (floor($j / 16)) * (floor((15238 * $j) / 43)) + 29;
        
        $month = floor((24 * $l) / 709);
        $day = $l - floor((709 * $month) / 24);
        $year = 30 * $n + $j - 30;

        return ['day' => $day, 'month' => $month, 'year' => $year];
    }

    public static function getSunnahType($gregorianDate)
    {
        $date = Carbon::parse($gregorianDate);
        $hijri = self::gregorianToHijri($date->day, $date->month, $date->year);
        $hijriDay = $hijri['day'];
        $hijriMonth = $hijri['month'];

        // Haram for fasting
        if (
            ($hijriMonth == 10 && $hijriDay == 1) || // Idul Fitri
            ($hijriMonth == 12 && $hijriDay == 10) || // Idul Adha
            ($hijriMonth == 12 && in_array($hijriDay, [11, 12, 13])) // Tasyrik
        ) {
            return 'haram';
        }

        // Arafah (9 Dzulhijjah)
        if ($hijriMonth == 12 && $hijriDay == 9) return 'arafah';

        // Tarwiyah (8 Dzulhijjah)
        if ($hijriMonth == 12 && $hijriDay == 8) return 'tarwiyah';
        
        // Tasu'a (9 Muharram)
        if ($hijriMonth == 1 && $hijriDay == 9) return 'tasua';

        // Ashura (10 Muharram)
        if ($hijriMonth == 1 && $hijriDay == 10) return 'ashura';

        // Ramadhan
        if ($hijriMonth == 9) return 'ramadhan';

        // Ayyamul Bidh (13, 14, 15 except in Tasyrik)
        if (in_array($hijriDay, [13, 14, 15])) return 'ayyamul_bidh';
        
        // Senin
        if ($date->dayOfWeek == Carbon::MONDAY) return 'senin';

        // Kamis
        if ($date->dayOfWeek == Carbon::THURSDAY) return 'kamis';

        return null;
    }
}

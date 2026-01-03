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

    public static function gregorianToHijri($d, $m, $y)
    {
        if (($y > 1582) || (($y == 1582) && ($m > 10)) || (($y == 1582) && ($m == 10) && ($d > 14))) {
            $jd = floor((1461 * ($y + 4800 + floor(($m - 14) / 12))) / 4) +
                  floor((367 * ($m - 2 - 12 * (floor(($m - 14) / 12)))) / 12) -
                  floor((3 * floor(($y + 4900 + floor(($m - 14) / 12)) / 100)) / 4) +
                  $d - 32075;
        } else {
            $jd = 367 * $y - floor((7 * ($y + 5001 + floor(($m - 9) / 7))) / 4) +
                  floor((275 * $m) / 9) + $d + 1729777;
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
}

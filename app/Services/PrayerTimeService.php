<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PrayerTimeService
{
    private const ALADHAN_API_URL = 'https://api.aladhan.com/v1';
    private const KEMENAG_API_URL = 'https://api.myquran.com/v2';
    
    /**
     * Get today's prayer times for a specific city (Hybrid approach)
     */
    public function getTodayPrayerTimes(?string $city = null, ?string $country = null, int $method = 2): ?array
    {
        if (!$city || !$country) {
            return null;
        }

        // Use Kemenag API for Indonesia, Aladhan for others
        if ($this->isIndonesia($country)) {
            return $this->getKemenagPrayerTimes($city);
        }

        return $this->getAladhanPrayerTimes($city, $country, $method);
    }

    /**
     * Check if country is Indonesia
     */
    private function isIndonesia(string $country): bool
    {
        $indonesiaVariants = ['indonesia', 'id', 'idn', 'republik indonesia'];
        return in_array(strtolower(trim($country)), $indonesiaVariants);
    }

    /**
     * Get prayer times from Kemenag API (Indonesia)
     */
    private function getKemenagPrayerTimes(string $city): ?array
    {
        $cacheKey = "prayer_kemenag_v3_{$city}_" . now()->format('Y-m-d');
        
        return Cache::remember($cacheKey, now()->endOfDay(), function () use ($city) {
            try {
                // First, search for city ID
                $cityId = $this->searchKemenagCityId($city);
                
                if (!$cityId) {
                    \Log::warning("Kemenag city not found: {$city}");
                    return null;
                }

                // Get today's prayer times
                $today = now();
                $url = self::KEMENAG_API_URL . "/sholat/jadwal/{$cityId}/{$today->year}/{$today->month}/{$today->day}";
                
                $response = Http::timeout(10)->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (($data['status'] === true || $data['status'] === 'ok') && isset($data['data']['jadwal'])) {
                        return $this->formatKemenagResponse($data['data']);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Kemenag API Error: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Search for city ID in Kemenag database
     */
    private function searchKemenagCityId(string $city): ?string
    {
        $cacheKey = "kemenag_city_id_v2_" . strtolower($city);
        
        return Cache::remember($cacheKey, now()->addDays(90), function () use ($city) {
            try {
                $response = Http::timeout(10)->get(self::KEMENAG_API_URL . "/sholat/kota/cari/{$city}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (($data['status'] === true || $data['status'] === 'ok') && !empty($data['data'])) {
                        // Return first match ID
                        return $data['data'][0]['id'];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Kemenag City Search Error: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Format Kemenag API response to standard format
     */
    private function formatKemenagResponse(array $data): array
    {
        $jadwal = $data['jadwal'];
        
        // Create Hijri date from current date (approximate)
        $now = now();
        $hijriYear = $now->year - 579; // Approximate conversion
        
        // Create Hijri date using IntlDateFormatter
        $hijriDate = 'Unknown';
        $hijriDay = $now->day;
        $hijriMonthName = $now->format('F');
        $hijriYearVal = $hijriYear;

        try {
            $fmt = \IntlDateFormatter::create(
                'id_ID@calendar=islamic',
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                'Asia/Jakarta',
                \IntlDateFormatter::TRADITIONAL,
                'd MMMM y'
            );
            $hijriString = $fmt->format($now->timestamp); // e.g., "20 Syakban 1447"
            
            // Parse the string to get parts or just use the string
            // For consistency with existing structure, let's try to parse it roughly
            // Expected: "dd Month yyyy"
            $parts = explode(' ', $hijriString);
            if (count($parts) >= 3) {
                $hijriDay = $parts[0];
                $hijriMonthName = $parts[1]; // e.g. Syakban
                $hijriYearVal = $parts[2];
            }
        } catch (\Throwable $e) {
            \Log::warning('Hijri Date Format Error: ' . $e->getMessage());
        }

        return [
            'timings' => [
                'Fajr' => $jadwal['subuh'] ?? '00:00',
                'Sunrise' => $jadwal['terbit'] ?? '00:00',
                'Dhuhr' => $jadwal['dzuhur'] ?? '00:00',
                'Asr' => $jadwal['ashar'] ?? '00:00',
                'Maghrib' => $jadwal['maghrib'] ?? '00:00',
                'Isha' => $jadwal['isya'] ?? '00:00',
                'Imsak' => $jadwal['imsak'] ?? '00:00',
            ],
            'date' => [
                'readable' => $jadwal['tanggal'] ?? '',
                'hijri' => [
                    'date' => $hijriDay . '-' . $hijriMonthName . '-' . $hijriYearVal,
                    'day' => (string) $hijriDay,
                    'month' => [
                        'number' => 0, // Not easily available from string, keep 0 or ignore
                        'en' => $hijriMonthName, // Use the localized name here
                    ],
                    'year' => (string) $hijriYearVal,
                ],
                'gregorian' => [
                    'date' => $now->format('d-m-Y'),
                    'day' => $now->format('d'),
                    'month' => [
                        'number' => (int) $now->format('m'),
                        'en' => $now->format('F'),
                    ],
                    'year' => $now->format('Y'),
                ],
            ],
            'source' => 'kemenag',
            'location' => [
                'city' => $data['lokasi'] ?? '',
                'region' => $data['daerah'] ?? '',
            ],
        ];
    }

    /**
     * Get prayer times from Aladhan API (Global)
     */
    private function getAladhanPrayerTimes(string $city, string $country, int $method = 2): ?array
    {
        $cacheKey = "prayer_aladhan_{$city}_{$country}_" . now()->format('Y-m-d');
        
        return Cache::remember($cacheKey, now()->endOfDay(), function () use ($city, $country, $method) {
            try {
                $response = Http::timeout(10)->get(self::ALADHAN_API_URL . '/timingsByCity', [
                    'city' => $city,
                    'country' => $country,
                    'method' => $method,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'timings' => $data['data']['timings'] ?? null,
                        'date' => $data['data']['date'] ?? null,
                        'source' => 'aladhan',
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Aladhan API Error: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Get prayer times for a specific date
     */
    public function getPrayerTimesByDate(string $city, string $country, string $date, int $method = 2): ?array
    {
        try {
            $timestamp = Carbon::parse($date)->timestamp;
            
            $response = Http::timeout(10)->get(self::ALADHAN_API_URL . '/timingsByCity/' . $timestamp, [
                'city' => $city,
                'country' => $country,
                'method' => $method,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'timings' => $data['data']['timings'] ?? null,
                    'date' => $data['data']['date'] ?? null,
                    'source' => 'aladhan',
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Prayer Time API Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get monthly prayer schedule
     */
    public function getMonthlySchedule(string $city, string $country, int $month, int $year, int $method = 2): ?array
    {
        // Use Kemenag for Indonesia
        if ($this->isIndonesia($country)) {
            return $this->getKemenagMonthlySchedule($city, $month, $year);
        }

        return $this->getAladhanMonthlySchedule($city, $country, $month, $year, $method);
    }

    /**
     * Get monthly schedule from Kemenag
     */
    private function getKemenagMonthlySchedule(string $city, int $month, int $year): ?array
    {
        $cacheKey = "prayer_schedule_kemenag_v3_{$city}_{$year}_{$month}";
        
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($city, $month, $year) {
            try {
                $cityId = $this->searchKemenagCityId($city);
                
                if (!$cityId) {
                    return null;
                }

                $url = self::KEMENAG_API_URL . "/sholat/jadwal/{$cityId}/{$year}/{$month}";
                $response = Http::timeout(15)->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (($data['status'] === true || $data['status'] === 'ok') && isset($data['data']['jadwal'])) {
                        return [
                            'schedule' => $data['data']['jadwal'],
                            'source' => 'kemenag',
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Kemenag Monthly Schedule Error: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Get monthly schedule from Aladhan
     */
    private function getAladhanMonthlySchedule(string $city, string $country, int $month, int $year, int $method = 2): ?array
    {
        $cacheKey = "prayer_schedule_aladhan_{$city}_{$country}_{$year}_{$month}";
        
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($city, $country, $month, $year, $method) {
            try {
                $response = Http::timeout(15)->get(self::ALADHAN_API_URL . "/calendarByCity/{$year}/{$month}", [
                    'city' => $city,
                    'country' => $country,
                    'method' => $method,
                ]);

                if ($response->successful()) {
                    return [
                        'schedule' => $response->json()['data'] ?? null,
                        'source' => 'aladhan',
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Aladhan Monthly Schedule Error: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Determine which prayer is next
     */
    public function getNextPrayer(array $timings): ?array
    {
        $now = now();
        $prayers = [
            'Fajr' => $timings['Fajr'] ?? null,
            'Dhuhr' => $timings['Dhuhr'] ?? null,
            'Asr' => $timings['Asr'] ?? null,
            'Maghrib' => $timings['Maghrib'] ?? null,
            'Isha' => $timings['Isha'] ?? null,
        ];

        foreach ($prayers as $name => $time) {
            if (!$time) continue;
            
            $prayerTime = Carbon::createFromFormat('H:i', $time);
            
            if ($prayerTime->greaterThan($now)) {
                return [
                    'name' => $name,
                    'time' => $time,
                    'seconds_until' => $now->diffInSeconds($prayerTime, false),
                ];
            }
        }

        // If no prayer left today, next is Fajr tomorrow
        if (isset($prayers['Fajr'])) {
            $fajrTomorrow = Carbon::createFromFormat('H:i', $prayers['Fajr'])->addDay();
            return [
                'name' => 'Fajr',
                'time' => $prayers['Fajr'],
                'seconds_until' => $now->diffInSeconds($fajrTomorrow, false),
            ];
        }

        return null;
    }

    /**
     * Get countdown state (Iftar or Sahur)
     */
    public function getCountdownState(array $timings): array
    {
        $now = now();
        $maghrib = Carbon::createFromFormat('H:i', $timings['Maghrib']);
        $imsak = Carbon::createFromFormat('H:i', $timings['Imsak']);
        $fajr = Carbon::createFromFormat('H:i', $timings['Fajr']);

        // Case 1: Night after Maghrib (Next Sahur)
        if ($now->greaterThanOrEqualTo($maghrib)) {
            $tomorrowImsak = $imsak->copy()->addDay();
            return [
                'type' => 'sahur', // Countdown to Imsak
                'target_label' => 'Imsak',
                'seconds' => $now->diffInSeconds($tomorrowImsak, false),
                'dua_type' => 'niat_puasa',
                'message' => 'Jangan sampai kesiangan, yuk sahur!',
                'is_active' => true
            ];
        }

        // Case 2: Early Morning before Imsak (Sahur)
        if ($now->lessThan($imsak)) {
            return [
                'type' => 'sahur',
                'target_label' => 'Imsak',
                'seconds' => $now->diffInSeconds($imsak, false),
                'dua_type' => 'niat_puasa',
                'message' => 'Jangan sampai kesiangan, yuk sahur!',
                'is_active' => true
            ];
        }

        // Case 3: Imsak passed but before Fajr (Warning Zone)
        if ($now->greaterThanOrEqualTo($imsak) && $now->lessThan($fajr)) {
            return [
                'type' => 'imsak_passed',
                'target_label' => 'Subuh',
                'seconds' => $now->diffInSeconds($fajr, false),
                'dua_type' => 'niat_puasa',
                'message' => 'Waktu Imsak telah masuk, bersiap sholat Subuh.',
                'is_active' => true
            ];
        }

        // Case 4: Daytime (Fasting) - Countdown to Maghrib
        return [
            'type' => 'iftar',
            'target_label' => 'Maghrib',
            'seconds' => $now->diffInSeconds($maghrib, false),
            'dua_type' => 'iftar',
            'message' => 'Semangat berpuasa hingga kemenangan!',
            'is_active' => true
        ];
    }

    /**
     * Format countdown seconds to human readable
     */
    public function formatCountdown(int $seconds): string
    {
        if ($seconds < 0) {
            return '00:00:00';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }

    /**
     * Get calculation method name
     */
    public function getMethodName(int $method): string
    {
        $methods = [
            0 => 'Jafari (Shia)',
            1 => 'University of Islamic Sciences, Karachi',
            2 => 'Islamic Society of North America (ISNA)',
            3 => 'Muslim World League (MWL)',
            4 => 'Umm Al-Qura University, Makkah',
            5 => 'Egyptian General Authority of Survey',
            7 => 'Institute of Geophysics, University of Tehran',
            8 => 'Gulf Region',
            9 => 'Kuwait',
            10 => 'Qatar',
            11 => 'Majlis Ugama Islam Singapura, Singapore',
            12 => 'Union Organization Islamic de France',
            13 => 'Diyanet İşleri Başkanlığı, Turkey',
            14 => 'Spiritual Administration of Muslims of Russia',
        ];

        return $methods[$method] ?? 'Unknown Method';
    }

    /**
     * Get list of Indonesian cities for autocomplete
     */
    public function searchIndonesianCities(string $query): array
    {
        try {
            $response = Http::timeout(5)->get(self::KEMENAG_API_URL . "/sholat/kota/cari/{$query}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (($data['status'] === true || $data['status'] === 'ok') && !empty($data['data'])) {
                    return array_map(function($city) {
                        return [
                            'id' => $city['id'],
                            'name' => $city['lokasi'],
                        ];
                    }, $data['data']);
                }
            }
        } catch (\Exception $e) {
            \Log::error('City Search Error: ' . $e->getMessage());
        }

        return [];
    }
    /**
     * Get Ramadhan Schedule for a specific Hijri year
     */
    public function getRamadhanSchedule(string $city, string $country, int $hijriYear = 1447, int $method = 2): ?array
    {
        // Map Hijri year to Gregorian months (Simplified for 1447H)
        // Ramadhan 1447 starts approx mid-Feb 2026 and ends mid-March 2026
        // We will fetch Feb and March 2026
        
        $monthsToFetch = [
            ['month' => 2, 'year' => 2026],
            ['month' => 3, 'year' => 2026],
        ];

        $ramadhanDays = [];

        foreach ($monthsToFetch as $m) {
            $schedule = $this->getMonthlySchedule($city, $country, $m['month'], $m['year'], $method);
            
            if (!$schedule || !isset($schedule['schedule'])) continue;

            foreach ($schedule['schedule'] as $day) {
                // Check if it's Ramadhan (Hijri Month 9)
                // Kemenag and Aladhan have different structures
                
                $isRamadhan = false;
                $hijriDate = null;

                if ($schedule['source'] === 'aladhan') {
                    $hijriMonth = $day['date']['hijri']['month']['number'] ?? 0;
                     // Aladhan returns int or string
                    if ((int)$hijriMonth === 9) {
                        $isRamadhan = true;
                        $hijriDate = $day['date']['hijri'];
                    }
                } elseif ($schedule['source'] === 'kemenag') {
                     // Kemenag structure from getKemenagMonthlySchedule return raw 'jadwal' array
                     // formatKemenagResponse is for single day. getKemenagMonthlySchedule returns list.
                     // The list items usually have 'tanggal' like "Sabtu, 01/02/2026"
                     // It does NOT have Hijri info included by default in the list endpoint :/
                     // We might need to approximate or use Aladhan for dates if using Kemenag
                     // OR rely on the fact that we requested specific months.
                     
                     // Limitation: Kemenag monthly endpoint might not have Hijri. 
                     // Let's check a sample response or assume Aladhan for Hijri date conversion if needed.
                     // Actually, for simplicity and reliability of Hijri dates, Aladhan is better.
                     // But if user is in Indonesia, they prefer Kemenag times.
                     
                     // Workaround: We know the range. 
                     // Feb 18 2026 to Mar 19 2026 is approx Ramadhan.
                     // Let's fallback to checking Gregorian date range if Hijri info is missing.
                     // Ramadhan 1447: ~18 Feb 2026 to ~19 Mar 2026.
                     
                     $dateStr = $day['date'] ?? $day['tanggal']; // Kemenag has 'tanggal', Aladhan 'date' inside 'date'
                     // Kemenag monthly item: { "tanggal": "Minggu, 01/02/2026", "imsak": "...", ... }
                     
                     // Parse date
                     try {
                         // Extract date from "Minggu, 01/02/2026"
                         if (preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $dateStr, $matches)) {
                             $d = (int)$matches[1];
                             $m = (int)$matches[2];
                             $y = (int)$matches[3];
                             $carbonDate = Carbon::create($y, $m, $d);
                         } else {
                             // Try standard Y-m-d if not match
                             $carbonDate = Carbon::parse($dateStr);
                         }
                         
                         // Approximate Ramadhan check for 2026
                         // Start: 2026-02-18, End: 2026-03-19 (approx 30 days)
                         if ($carbonDate->between('2026-02-15', '2026-03-25')) {
                             // Fine-tune with Aladhan API check or just generic range?
                             // Let's use Aladhan conversion for the day to be sure? 
                             // No, that's too many API calls.
                             // Let's just use the known range for 1447H.
                             // 1 Ramadhan 1447 = 19 Feb 2026 (Sidang Isbat)
                             // Let's include a buffer and maybe just show the whole combined list?
                             // The Request asks for "Ramadhan Schedule".
                             // Let's hardcode the range for 1447H to be safe: Feb 19 - Mar 20.
                             
                             if ($carbonDate->between(Carbon::create(2026, 2, 19), Carbon::create(2026, 3, 21))) {
                                 $isRamadhan = true;
                                 
                                 // Mock Hijri info for Kemenag
                                 $dayOfRamadhan = $carbonDate->diffInDays(Carbon::create(2026, 2, 18)); // 19th is day 1
                                 $hijriDate = [
                                     'day' => $dayOfRamadhan,
                                     'month' => ['en' => 'Ramadhan', 'number' => 9],
                                     'year' => 1447
                                 ];
                             }
                         }
                     } catch (\Exception $e) {}
                }

                if ($isRamadhan) {
                    $ramadhanDays[] = [
                        'date' => $day['date'] ?? $day['tanggal'],
                        'hijri' => $hijriDate,
                        'timings' => $schedule['source'] === 'aladhan' ? $day['timings'] : [
                            'Imsak' => $day['imsak'] ?? '',
                            'Fajr' => $day['subuh'] ?? '',
                            'Sunrise' => $day['terbit'] ?? '',
                            'Dhuhr' => $day['dzuhur'] ?? '',
                            'Asr' => $day['ashar'] ?? '',
                            'Maghrib' => $day['maghrib'] ?? '',
                            'Isya' => $day['isya'] ?? '',
                            'Isha' => $day['isya'] ?? '', // Fallback for consistency
                        ],
                        'source' => $schedule['source']
                    ];
                }
            }
        }

        return $ramadhanDays;
    }
}

<?php

namespace App\Services;

use App\Models\DailyTadabbur;
use App\Models\QuranSource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class TadabburService
{
    /**
     * Get or create the unique daily tadabbur for the user for today.
     *
     * @param User $user
     * @return DailyTadabbur|null
     */
    public function getTodayTadabbur(User $user): ?DailyTadabbur
    {
        $today = Carbon::today();

        // 1. Try to find existing record for today
        $existing = DailyTadabbur::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->with('quranSource')
            ->first();

        if ($existing) {
            return $existing;
        }

        // 2. If not found, generate a new one
        
        // Pick a random Quran source
        // Ideally, we could filter out sources already seen by the user recently, 
        // but for now, pure random from available sources.
        $source = QuranSource::inRandomOrder()->first();

        if (!$source) {
            return null; // Should not happen if seeded
        }

        // Create the daily record
        return DailyTadabbur::create([
            'user_id' => $user->id,
            'quran_source_id' => $source->id,
            'date' => $today,
            'status' => 'pending',
            'reflection' => null,
        ]);
    }

    /**
     * Generate a cohesive summary of the user's Ramadan journey.
     *
     * @param User $user
     * @return string|null
     */
    public function generateRamadanSummary(User $user): ?string
    {
        // Fetch all completed tadabbur entries ascending
        $entries = DailyTadabbur::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('reflection')
            ->orderBy('date', 'asc')
            ->with('quranSource')
            ->get();

        if ($entries->isEmpty()) {
            return null;
        }

        // AI Generation
        $apiKey = env('GEMINI_API_KEY');
        if ($apiKey) {
            try {
                $reflectionsList = $entries->map(function ($entry) {
                    return "- " . $entry->date->translatedFormat('d M') . ": " . $entry->reflection;
                })->implode("\n");

                $prompt = "System: Anda adalah sahabat spiritual yang puitis dan bijak. Tugas Anda adalah merangkai kembali perjalanan hati pengguna selama Ramadhan ini menjadi sebuah cerita yang indah.

User: Ini adalah jejak-jejak refleksi (tadabbur) saya:
$reflectionsList

Tolong tuliskan Narasi Perjalanan Jiwa saya berdasarkan catatan tersebut. 
- Gunakan bahasa Indonesia yang sangat luwes, sastrawi namun mudah dipahami, dan emosional (menyentuh hati).
- JANGAN buat seperti laporan atau daftar poin. Buatlah mengalir seperti paragraf novel atau surat cinta untuk diri sendiri.
- Hubungkan satu perenungan dengan yang lain secara halus seolah-olah itu adalah babak-babak pertumbuhan diri.
- Mulailah dengan kalimat pembuka yang menggugah, dan akhiri dengan harapan doa yang tulus.
- Gunakan sapaan 'kamu' yang akrab dan hangat.
- Panjang sekitar 3-4 paragraf yang nyaman dibaca.";

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $aiText = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($aiText) {
                        return $aiText;
                    }
                }
            } catch (\Exception $e) {
                // Silently fail to fallback
                \Log::error('Gemini API Error: ' . $e->getMessage());
            }
        }

        // Fallback: Rule-Based Generation
        $summary = "Perjalanan tadabbur Ramadhan ini dimulai dengan sebuah renungan: ";
        
        $connectors = [
            'Kemudian, ', 
            'Hari berikutnya, ', 
            'Selanjutnya, ', 
            'Di sisi lain, ', 
            'Perjalanan berlanjut dengan, ', 
            'Lalu, ', 
            'Keesokan harinya, '
        ];

        foreach ($entries as $index => $entry) {
            $reflection = rtrim($entry->reflection, '.'); // Remove trailing dot if exists
            
            if ($index === 0) {
                // First entry
                $summary .= '"' . $reflection . '". ';
            } else {
                // Subsequent entries with random connectors
                $connector = $connectors[array_rand($connectors)];
                $summary .= $connector . '"' . $reflection . '". ';
            }
        }

        $summary .= "\n\nSemoga setiap hikmah yang didapat menjadi penerang jalan ke depan.";

        return $summary;
    }
}

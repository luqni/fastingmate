<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuranSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Truncating quran_sources table...');
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\QuranSource::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $this->command->info('Fetching Quran data...');
        
        // 1. Fetch Arabic Text and Structure from risan/quran-json
        $arabicUrl = 'https://raw.githubusercontent.com/risan/quran-json/master/dist/quran.json';
        $arabicContent = file_get_contents($arabicUrl);
        if ($arabicContent === false) throw new \Exception("Failed to download Arabic data");
        $arabicData = json_decode($arabicContent, true);

        // 2. We need translation. Since single file is hard to find, we will use gadingnst/quran-json per surah key
        // Base URL: https://raw.githubusercontent.com/gadingnst/quran-json/master/surah/{id}.json
        // To be faster, we can try to fetch all translations if possible, but let's just loop. It takes time but run once.
        
        $this->command->info('Seeding Quran verses... This will take some time due to fetching translations.');
        $batchSize = 200;
        $batch = [];

        foreach ($arabicData as $surah) {
            $surahId = $surah['id'];
            $surahName = $surah['transliteration']; // "Al-Fatihah"

            $this->command->info("Processing Surah {$surahId}: {$surahName}");

            // Fetch translation for this surah
            // Using sutanlab or gadingnst. gadingnst has 'verses' array with 'translation' key?
            // Let's use sutanlab as I verified it has "data" wrapper but I didn't verify verses structure in full.
            // Let's use https://raw.githubusercontent.com/nomorf/quran-json/master/surah/{id}.json 
            // Wait, let's use the one commonly used: https://raw.githubusercontent.com/awaisark/quran-json/master/surahs/translation/{id}.json ? No.
            
            // Let's try to just USE the arabic text only if translation fails? No, requirement is translation.
            // Let's use https://raw.githubusercontent.com/penggguna/QuranJSON/master/surah/{id}.json
            // Code below assumes penggguna structure or similar.
            
            $transUrl = "https://raw.githubusercontent.com/penggguna/QuranJSON/master/surah/{$surahId}.json";
            $transContent = @file_get_contents($transUrl);
            $transData = $transContent ? json_decode($transContent, true) : null;
            
            // Map verses from Arabic Data (reliable) and try to match translation
            foreach ($surah['verses'] as $index => $verse) {
                $verseId = $verse['id'];
                $arabicText = $verse['text'];
                
                // Try to find translation
                $translation = '';
                if ($transData && isset($transData['verses'][$index]['translation'])) {
                    $translation = $transData['verses'][$index]['translation'];
                } elseif ($transData && isset($transData['name_translations'])) {
                     // Fallback/Error?
                }
                
                // If penggguna fails/different structure, we might have empty translation. 
                // Let's try to map keys safely.
                // penggguna verses: "number": 1, "text": "...", "translation_id": "..."
                if (empty($translation) && isset($transData['verses'])) {
                     foreach($transData['verses'] as $v) {
                         // Some APIs use 'number' or 'id'
                         $vId = $v['number'] ?? $v['id'] ?? null;
                         if ($vId == $verseId) {
                             $translation = $v['translation_id'] ?? $v['translation'] ?? '';
                             break;
                         }
                     }
                }

                $batch[] = [
                    'surah_name' => $surahName,
                    'ayah_number' => $verseId,
                    'ayah_text_arabic' => $arabicText,
                    'ayah_translation' => $translation ?: 'Terjemahan tidak tersedia',
                    'created_at' => now(), 
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    \App\Models\QuranSource::insert($batch);
                    $batch = [];
                }
            }
        }

        if (!empty($batch)) {
            \App\Models\QuranSource::insert($batch);
        }

        $this->command->info('Quran data seeded successfully!');
    }
}

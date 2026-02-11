<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PopulateQuranPages extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/quran_meta.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error("File quran_meta.json not found in database/seeders/data/!");
            return;
        }

        // Check if pages are already populated
        if (DB::table('quran_sources')->whereNotNull('page')->exists()) {
            $this->command->info('Quran pages already populated. Skipping seeder.');
            return;
        }

        $data = json_decode(File::get($jsonPath), true);
        $surahs = $data['data']['surahs'];
        
        // Get all unique surah names from DB to map them
        $dbSurahNames = DB::table('quran_sources')
            ->select('surah_name')
            ->distinct()
            ->pluck('surah_name')
            ->toArray();

        $this->command->info("Found " . count($dbSurahNames) . " Surahs in DB.");

        foreach ($surahs as $surah) {
            $jsonName = $surah['englishName']; // e.g., "Al-Faatiha"
            $surahNumber = $surah['number'];

            // Find matching DB name
            $matchedDbName = null;
            $highestSimilarity = 0;

            foreach ($dbSurahNames as $dbName) {
                // strict match first
                if (strtolower($dbName) === strtolower($jsonName)) {
                    $matchedDbName = $dbName;
                    break;
                }
                
                // fuzzy match
                similar_text(strtolower($dbName), strtolower($jsonName), $percent);
                if ($percent > $highestSimilarity && $percent > 80) {
                    $highestSimilarity = $percent;
                    $matchedDbName = $dbName;
                }
                
                // Fallback: Check if one contains the other (e.g. Al-Fatihah vs Al-Faatiha)
                // Remove hyphens and 'Al'
                $cleanDb = str_replace(['Al-', 'As-', 'At-', 'An-', 'Az-', 'Ar-', 'Ash-', 'Ad-', '-'], '', $dbName);
                $cleanJson = str_replace(['Al-', 'As-', 'At-', 'An-', 'Az-', 'Ar-', 'Ash-', 'Ad-', '-'], '', $jsonName);
                
                if (str_contains(strtolower($cleanDb), strtolower($cleanJson)) || str_contains(strtolower($cleanJson), strtolower($cleanDb))) {
                     if ($percent < 90) { // priorize similar_text if high
                         // Keep potential match but prefer exact/high similarity
                     }
                }
            }
            
            // Special overrides if auto-match fails or is wrong
            if ($jsonName === 'Al-Faatiha') $matchedDbName = 'Al-Fatihah';
            if ($jsonName === 'Al-Baqara') $matchedDbName = 'Al-Baqarah';
            if ($jsonName === 'Aal-i-Imraan') $matchedDbName = "Ali 'Imran";
            if ($jsonName === 'At-Taubah') $matchedDbName = 'At-Tawbah'; // JSON might be different
            if ($jsonName === 'Yusuf') $matchedDbName = 'Yusuf'; // Match
             // ... Add more if needed. Let's rely on log to see what matched.

            if ($matchedDbName) {
                $this->command->info("Mapped Json: $jsonName -> DB: $matchedDbName");
                
                foreach ($surah['ayahs'] as $ayah) {
                    DB::table('quran_sources')
                        ->where('surah_name', $matchedDbName)
                        ->where('ayah_number', $ayah['numberInSurah'])
                        ->update([
                            'page' => $ayah['page'],
                            'juz' => $ayah['juz']
                        ]);
                }
            } else {
                $this->command->warn("Could not match Surah: $jsonName");
            }
        }
        
        $this->command->info("Data population completed.");
    }
}

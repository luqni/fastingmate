<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Execute the script to fix missing pages
        $map = [
            20 => 'Taha', 
            24 => 'An-Nur', 
            30 => 'Ar-Rum', 
            36 => 'Ya-Sin', 
            71 => 'Nuh'
        ];
        
        foreach($map as $id => $name) { 
            // Fetch from API
            $json = @file_get_contents("https://api.quran.com/api/v4/verses/by_chapter/{$id}?fields=page_number&per_page=300"); 
            if (!$json) continue;
            
            $data = json_decode($json, true); 
            if (isset($data['verses'])) {
                foreach($data['verses'] as $verse) { 
                    \App\Models\QuranSource::where('surah_name', $name)
                        ->where('ayah_number', $verse['verse_number'])
                        ->update(['page' => $verse['page_number']]); 
                } 
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No clear reverse since we don't safely know what was null before, 
        // but we could set them back to null if needed.
    }
};

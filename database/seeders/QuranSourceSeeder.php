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
        \App\Models\QuranSource::insert([
            [
                'surah_name' => 'Al-Baqarah',
                'ayah_number' => 186,
                'ayah_text_arabic' => 'وَإِذَا سَأَلَكَ عِبَادِي عَنِّي فَإِنِّي قَرِيبٌ ۖ أُجِيبُ دَعْوَةَ الدَّاعِ إِذَا دَعَانِ ۖ فَلْيَسْتَجِيبُوا لِي وَلْيُؤْمِنُوا bِي لَعَلَّهُمْ يَرْشُدُونَ',
                'ayah_translation' => 'Dan apabila hamba-hamba-Ku bertanya kepadamu tentang Aku, maka (jawablah), bahwasanya Aku adalah dekat. Aku mengabulkan permohonan orang yang berdoa apabila ia memohon kepada-Ku...',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'surah_name' => 'Al-Insyirah',
                'ayah_number' => 5,
                'ayah_text_arabic' => 'فَإِنَّ مَعَ الْعُسْرِ يُسْرًا',
                'ayah_translation' => 'Karena sesungguhnya sesudah kesulitan itu ada kemudahan.',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'surah_name' => 'Al-Baqarah',
                'ayah_number' => 286,
                'ayah_text_arabic' => 'لَا يُكَلِّفُ اللَّهُ نَفْسًا إِلَّا وُسْعَهَا',
                'ayah_translation' => 'Allah tidak membebani seseorang melainkan sesuai dengan kesanggupannya.',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'surah_name' => 'Ar-Ra\'d',
                'ayah_number' => 28,
                'ayah_text_arabic' => 'أَلَا بِذِكْرِ اللَّهِ تَطْمَئِنُّ الْقُلُوبُ',
                'ayah_translation' => 'Ingatlah, hanya dengan mengingati Allah-lah hati menjadi tenteram.',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'surah_name' => 'Al-Imran',
                'ayah_number' => 139,
                'ayah_text_arabic' => 'وَلَا تَهِنُوا وَلَا تَحْزَنُوا وَأَنتُمُ الْأَعْلَوْنَ إِن كُنتُم مُّؤْمِنِينَ',
                'ayah_translation' => 'Janganlah kamu bersikap lemah, dan janganlah (pula) kamu bersedih hati, padahal kamulah orang-orang yang paling tinggi (derajatnya), jika kamu orang-orang yang beriman.',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

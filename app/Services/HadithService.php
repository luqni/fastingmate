<?php

namespace App\Services;

class HadithService
{
    /**
     * Get a random Hadith from the collection.
     * 
     * @return array
     */
    public function getRandomHadith()
    {
        $hadiths = $this->getSeededHadiths();
        return $hadiths[array_rand($hadiths)];
    }

    private function getSeededHadiths()
    {
        return [
            [
                'source' => 'HR. Bukhari',
                'text' => 'Tidak akan beriman salah seorang di antara kamu hingga ia mencintai untuk saudaranya apa yang ia cintai untuk dirinya sendiri.'
            ],
            [
                'source' => 'HR. Muslim',
                'text' => 'Dunia adalah perhiasan, dan sebaik-baik perhiasan dunia adalah wanita sholehah.'
            ],
            [
                'source' => 'HR. Bukhari & Muslim',
                'text' => 'Barangsiapa yang beriman kepada Allah dan hari akhir, maka hendaklah ia berkata baik atau diam.'
            ],
            [
                'source' => 'HR. Bukhari',
                'text' => 'Sebaik-baik kamu adalah orang yang mempelajari Al-Qur\'an dan mengajarkannya.'
            ],
            [
                'source' => 'HR. Muslim',
                'text' => 'Shalat lima waktu, Jumat ke Jumat, dan Ramadhan ke Ramadhan adalah penghapus dosa-dosa di antara keduanya, selama dosa-dosa besar dijauhi.'
            ],
            [
                'source' => 'HR. Bukhari',
                'text' => 'Dua kalimat yang ringan di lisan, namun berat di timbangan, dan dicintai Ar-Rahman: Subhanallahi wa bihamdih, Subhanallahil \'adzim.'
            ],
            [
                'source' => 'HR. Muslim',
                'text' => 'Senyummu di hadapan saudaramu adalah sedekah.'
            ],
            [
                'source' => 'HR. Bukhari',
                'text' => 'Bertaqwalah kepada Allah di mana pun engkau berada, dan iringilah keburukan dengan kebaikan, niscaya kebaikan itu akan menghapusnya, dan pergaulilah manusia dengan akhlak yang mulia.'
            ],
            [
                'source' => 'HR. Muslim',
                'text' => 'Sesungguhnya Allah tidak melihat kepada rupa dan harta kalian, tetapi Allah melihat kepada hati dan amal kalian.'
            ],
            [
                'source' => 'HR. Bukhari',
                'text' => 'Barangsiapa yang ingin dilapangkan rezekinya dan dipanjangkan umurnya, maka hendaklah ia menyambung tali silaturahmi.'
            ],
            [
                'source' => 'HR. Muslim',
                'text' => 'Agama itu adalah nasihat.'
            ],
             [
                'source' => 'HR. Bukhari',
                'text' => 'Setiap amal itu tergantung niatnya, dan setiap orang hanya mendapatkan apa yang ia niatkan.'
            ],
            [
                'source' => 'HR. Bukhari & Muslim',
                'text' => 'Jagalah dirimu dari api neraka walau hanya dengan (bersedekah) separuh biji kurma.'
            ],
            [
                'source' => 'HR. Muslim',
                'text' => 'Barangsiapa menempuh suatu jalan untuk menuntut ilmu, maka Allah akan mudahkan baginya jalan menuju surga.'
            ],
            [
                'source' => 'HR. Bukhari',
                'text' => 'Orang mukmin yang kuat lebih baik dan lebih dicintai Allah daripada orang mukmin yang lemah, namun pada keduanya ada kebaikan.'
            ]
        ];
    }
}

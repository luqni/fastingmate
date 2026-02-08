<?php

namespace App\Http\Controllers;

use App\Models\QuranSource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuranController extends Controller
{
    public function index()
    {
        // Get all distinct surahs with their first verse to get usage count or just names
        // Since we don't have surah_number, we rely on the ID order which usually follows Quran order if seeded sequentially
        // We will fetch all unique surah names ordered by min(id)
        
        $surahs = QuranSource::select('surah_name')
            ->selectRaw('MIN(id) as first_id')
            ->groupBy('surah_name')
            ->orderBy('first_id')
            ->orderBy('first_id')
            ->get();

        $lastRead = \App\Models\QuranProgress::where('user_id', auth()->id())->latest('updated_at')->first();

        return view('quran.index', compact('surahs', 'lastRead'));
    }

    public function show($surah)
    {
        $decodedSurah = urldecode($surah);
        
        // 1. Try Exact Match
        $quran = QuranSource::where('surah_name', $decodedSurah)->orderBy('id')->get();
        
        if ($quran->isEmpty()) {
            // 2. Try replacing hyphens with spaces (e.g. Al-Fatihah -> Al Fatihah)
            $withSpaces = str_replace('-', ' ', $decodedSurah);
            $quran = QuranSource::where('surah_name', 'LIKE', $withSpaces)->orderBy('id')->get();
        }

        if ($quran->isEmpty()) {
             // 3. Try replacing spaces with hyphens (e.g. Ali Imran -> Ali-Imran) - less likely but possible
             $withHyphens = str_replace(' ', '-', $decodedSurah);
             $quran = QuranSource::where('surah_name', 'LIKE', $withHyphens)->orderBy('id')->get();
        }

        if ($quran->isEmpty()) {
            // 4. Try matching without Prefix (Al-, As-, etc) for loose fuzzy matching
            $simpleName = preg_replace('/^(Al-|As-|At-|An-|Az-|Ar-|Ad-|Ash-)/', '', $decodedSurah);
            $quran = QuranSource::where('surah_name', 'LIKE', "%{$simpleName}%")
                ->orderBy('id')
                ->get();
        }

        if ($quran->isEmpty()) {
             return back()->with('error', 'Surah tidak ditemukan: ' . $decodedSurah);
        }

        $title = $quran->first()->surah_name;

        // Navigation Logic
        // Get all Surah names in order
        $allSurahs = QuranSource::select('surah_name')
            ->selectRaw('MIN(id) as first_id')
            ->groupBy('surah_name')
            ->orderBy('first_id')
            ->pluck('surah_name')
            ->values();

        $currentIndex = $allSurahs->search($title);
        
        $prevSurah = $currentIndex > 0 ? $allSurahs[$currentIndex - 1] : null;
        $nextSurah = $currentIndex < $allSurahs->count() - 1 ? $allSurahs[$currentIndex + 1] : null;

        // Group by Page Number for Mushaf View
        $pages = $quran->groupBy('page');

        return view('quran.show', compact('quran', 'pages', 'title', 'prevSurah', 'nextSurah'));
    }

    public function saveBookmark(Request $request)
    {
        $request->validate([
            'surah_name' => 'required',
            'ayah_number' => 'required',
            'page' => 'required'
        ]);

        \App\Models\QuranProgress::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'surah_name' => $request->surah_name,
                'ayah_number' => $request->ayah_number,
                'page' => $request->page,
                'updated_at' => now()
            ]
        );

        return response()->json(['message' => 'Penanda disimpan']);
    }

    public function continueReading()
    {
        $progress = \App\Models\QuranProgress::where('user_id', auth()->id())->latest('updated_at')->first();

        if ($progress) {
            return redirect()->route('quran.show', [
                'surah' => $progress->surah_name, 
                'page' => $progress->page // passing page as query param if needed to jump
            ])->with('jump_to_page', $progress->page);
        }

        return redirect()->route('quran.index')->with('error', 'Belum ada riwayat membaca.');
    }
}

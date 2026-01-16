<?php

namespace App\Http\Controllers;

use App\Models\DailyTadabbur;
use Illuminate\Http\Request;

class TadabburController extends Controller
{
    /**
     * Display a listing of the completed tadabbur history.
     */
    public function index()
    {
        $histories = DailyTadabbur::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->with('quranSource')
            ->latest('date')
            ->get();

        $enableRamadanSummary = \App\Models\Setting::where('key', 'enable_ramadan_summary')->value('value') === '1';
        
        $currentYear = date('Y');
        $summary = \App\Models\RamadanSummary::where('user_id', auth()->id())
            ->where('year', $currentYear)
            ->value('content');

        return view('tadabbur.index', compact('histories', 'enableRamadanSummary', 'summary'));
    }

    public function generateSummary()
    {
        $enableRamadanSummary = \App\Models\Setting::where('key', 'enable_ramadan_summary')->value('value') === '1';
        
        if (!$enableRamadanSummary) {
            return back()->with('error', 'Fitur ini sedang dinonaktifkan.');
        }

        $summaryContent = app(\App\Services\TadabburService::class)->generateRamadanSummary(auth()->user());
        
        if (!$summaryContent) {
            return back()->with('error', 'Belum ada cukup data untuk membuat rangkuman.');
        }

        // Save to Database
        \App\Models\RamadanSummary::updateOrCreate(
            [
                'user_id' => auth()->id(), 
                'year' => date('Y')
            ],
            ['content' => $summaryContent]
        );

        return back()->with('success', 'Rangkuman berhasil dibuat dan disimpan!');
    }

    /**
     * Update the daily tadabbur with a reflection.
     */
    public function store(Request $request, DailyTadabbur $dailyTadabbur)
    {
        // Ensure the user owns this record
        if ($dailyTadabbur->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'reflection' => 'required|string|min:10',
        ]);

        $dailyTadabbur->update([
            'reflection' => $validated['reflection'],
            'status' => 'completed',
        ]);

        return back()->with('status', 'reflection-saved'); // We'll use this for toast/notification
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DailyTadabbur;
use Illuminate\Http\Request;

class TadabburController extends Controller
{
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

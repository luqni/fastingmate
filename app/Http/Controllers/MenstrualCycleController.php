<?php

namespace App\Http\Controllers;

use App\Models\MenstrualCycle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenstrualCycleController extends Controller
{
    public function index()
    {
        $cycles = Auth::user()->menstrualCycles()->latest('start_date')->get();
        return view('menstrual-cycles.index', compact('cycles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:today',
        ]);

        // Check if there is an active cycle
        $activeCheck = Auth::user()->menstrualCycles()->whereNull('end_date')->exists();
        if ($activeCheck) {
            return back()->with('error', 'Anda masih memiliki siklus haid yang aktif.');
        }

        Auth::user()->menstrualCycles()->create([
            'start_date' => $request->start_date,
        ]);

        return redirect()->route('menstrual-cycles.index')->with('success', 'Haid dimulai.');
    }

    public function update(Request $request, MenstrualCycle $menstrualCycle)
    {
        if ($menstrualCycle->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ]);

        $menstrualCycle->update([
            'end_date' => $request->end_date
        ]);

        return back()->with('success', 'Haid selesai. Hutang puasa telah ditambahkan.');
    }
    public function destroy(MenstrualCycle $menstrualCycle)
    {
        if ($menstrualCycle->user_id !== Auth::id()) {
            abort(403);
        }

        $menstrualCycle->delete();

        return back()->with('success', 'Riwayat haid berhasil dihapus.');
    }
}

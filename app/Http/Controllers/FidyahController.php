<?php

namespace App\Http\Controllers;

use App\Models\FidyahRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FidyahController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $debts = $user->fastingDebts()->where('is_paid_off', false)->get();
        $totalDays = 0;
        $totalFidyahCost = 0;
        
        // Default Rate
        $defaultRate = $user->fidyah_cost ?? (FidyahRate::first()?->price_per_day ?? 15000);
        $currentYear = date('Y');

        $breakdown = [];

        foreach ($debts as $debt) {
            $remaining = $debt->total_days - $debt->paid_days;
            // Logic: If debt is from 2023 and now is 2025:
            // 2023's Ramadan was ~Mar 2023. Fasting for 2023 should be done before Ramadan 2024.
            // If we are in 2025, it's late by 1 year? 
            // Simplified rule for app feature: Multiplier = (Current Year - Debt Year). 
            // If Debt Year == Current Year, Multiplier = 1.
            
            $multiplier = max(0, $currentYear - $debt->year);
            
            $cost = $remaining * $defaultRate * $multiplier;
            
            $totalDays += $remaining; // Actual days missed
            $totalFidyahCost += $cost;

            $breakdown[] = [
                'year' => $debt->year,
                'days' => $remaining,
                'multiplier' => $multiplier,
                'cost' => $cost
            ];
        }
        
        return view('fidyah.index', compact('totalDays', 'totalFidyahCost', 'defaultRate', 'breakdown'));
    }

    public function store(Request $request)
    {
        $request->validate(['rate' => 'required|numeric|min:0']);
        
        $user = Auth::user();
        $user->fidyah_cost = $request->rate;
        $user->save(); // This will trigger the mutator to save into preferences

        return back()->with('success', 'Biaya Fidyah berhasil disimpan.');
    }
}

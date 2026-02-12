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
        $request->merge(['rate' => str_replace('.', '', $request->rate)]);
        $request->validate(['rate' => 'required|numeric|min:0']);
        
        $user = Auth::user();
        $user->fidyah_cost = $request->rate;
        $user->save(); // This will trigger the mutator to save into preferences

        return back()->with('success', 'Biaya Fidyah berhasil disimpan.');
    }

    public function pay(Request $request)
    {
        // Sanitize currency inputs (remove dots)
        $request->merge([
            'amount' => str_replace('.', '', $request->amount),
            'rate' => str_replace('.', '', $request->rate),
        ]);

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'rate' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $rate = $request->rate;
        
        // Calculate total days paid based on rate
        $daysPaidTotal = floor($amount / $rate);
        
        if ($daysPaidTotal < 1) {
             return back()->with('error', 'Nominal tidak cukup untuk membayar 1 hari fidyah.');
        }

        // Calculate total unpaid days first
        $totalUnpaidDays = $user->fastingDebts()->where('is_paid_off', false)->get()->sum(function($debt) {
            return $debt->total_days - $debt->paid_days;
        });

        if ($daysPaidTotal > $totalUnpaidDays) {
            return back()->with('error', "Gagal: Nominal yang dibayarkan setara dengan $daysPaidTotal hari, sedangkan sisa hutang Anda hanya $totalUnpaidDays hari.");
        }

        // Get unpaid debts ordered by oldest year first
        $debts = $user->fastingDebts()
            ->where('is_paid_off', false)
            ->orderBy('year', 'asc')
            ->get();
            
        $remainingDaysToPay = $daysPaidTotal;
        $processed = 0;

        foreach ($debts as $debt) {
            if ($remainingDaysToPay <= 0) break;

            $debtRemaining = $debt->total_days - $debt->paid_days;
            
            // How many days we can pay for this specific debt year
            $payForThisDebt = min($remainingDaysToPay, $debtRemaining);
            
            // Update debt progress
            $newPaidTotal = $debt->paid_days + $payForThisDebt;
            $debt->update([
                'paid_days' => $newPaidTotal,
                'is_paid_off' => $newPaidTotal >= $debt->total_days
            ]);
            
            // Create repayment record
            // Calculate cost portion for this record: days * rate
            // Note: We ignore multiplier for *recording* the repayment days count, 
            // but in real world Fidyah value might differ. 
            // Here we assume the user pays 'amount' which covers 'days' at 'rate'.
            // The monetary value recorded in description is proportional.
            $costForThis = $payForThisDebt * $rate; 
            
            $debt->repayments()->create([
                'paid_days' => $payForThisDebt,
                'repayment_date' => now(),
                'description' => "Bayar Fidyah (Rp " . number_format($costForThis, 0, ',', '.') . ")",
            ]);

            $remainingDaysToPay -= $payForThisDebt;
            $processed += $payForThisDebt;
        }

        if ($remainingDaysToPay > 0) {
            // Overpayment case (User paid for more days than they owe)
            // We could either store as balance or just notify.
            // For now, let's just notify.
            return back()->with('success', "Pembayaran diterima. $processed hari lunas. Ada kelebihan bayar setara $remainingDaysToPay hari.");
        }

        return back()->with('success', "Alhamdulillah, pembayaran fidyah untuk $processed hari berhasil dicatat.");
    }
}

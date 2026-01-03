<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FastingDebtController;
use App\Http\Controllers\FastingPlanController;
use App\Http\Controllers\FidyahController;
use App\Http\Controllers\MenstrualCycleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fasting Debts
    Route::resource('fasting-debts', FastingDebtController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/fasting-debts/{fastingDebt}/generate-schedule', [FastingDebtController::class, 'generateSchedule'])->name('fasting-debts.generate-schedule');
    Route::post('/fasting-debts/{fastingDebt}/update-progress', [FastingDebtController::class, 'updateProgress'])->name('fasting-debts.update-progress');
    Route::get('/fasting-debts/{fastingDebt}/history', [FastingDebtController::class, 'history'])->name('fasting-debts.history');

    // Menstrual Cycles
    Route::resource('menstrual-cycles', MenstrualCycleController::class)->only(['index', 'store', 'update', 'destroy']);

    // Fidyah
    Route::get('/fidyah', [FidyahController::class, 'index'])->name('fidyah.index');

    // Fasting Plans
    Route::resource('fasting-plans', FastingPlanController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

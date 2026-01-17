<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FastingDebtController;
use App\Http\Controllers\FastingPlanController;
use App\Http\Controllers\FidyahController;
use App\Http\Controllers\MenstrualCycleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Blog (Public)
Route::get('/blog', [\App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post:slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('posts.show');

// Track visits globally or on dashboard
Route::middleware(\App\Http\Middleware\TrackVisits::class)->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin Routes
        // Admin Routes
        Route::middleware(\App\Http\Middleware\EnsureUserIsAdmin::class)->prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
            Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
            Route::post('/posts/{post}/unlock', [\App\Http\Controllers\Admin\PostController::class, 'unlock'])->name('posts.unlock');
            Route::post('/posts/upload-image', [\App\Http\Controllers\Admin\PostImageController::class, 'store'])->name('posts.upload.image');
            
            // Settings
            Route::get('/settings', [\App\Http\Controllers\Admin\AdminController::class, 'settings'])->name('settings.index');
            Route::post('/settings', [\App\Http\Controllers\Admin\AdminController::class, 'updateSettings'])->name('settings.update');
        });
        
        Route::post('/track-install', [\App\Http\Controllers\Admin\AdminController::class, 'trackInstall'])->name('track.install');

        // Fasting Debts
    Route::resource('fasting-debts', \App\Http\Controllers\FastingDebtController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/fasting-debts/{fastingDebt}/generate-schedule', [FastingDebtController::class, 'generateSchedule'])->name('fasting-debts.generate-schedule');
    Route::post('/fasting-debts/{fastingDebt}/update-progress', [FastingDebtController::class, 'updateProgress'])->name('fasting-debts.update-progress');
    Route::get('/fasting-debts/{fastingDebt}/history', [FastingDebtController::class, 'history'])->name('fasting-debts.history');

    // Menstrual Cycles
    Route::resource('menstrual-cycles', MenstrualCycleController::class)->only(['index', 'store', 'update', 'destroy']);

    // Fidyah
    Route::get('/fidyah', [FidyahController::class, 'index'])->name('fidyah.index');
    Route::post('/fidyah/update-rate', [FidyahController::class, 'store'])->name('fidyah.update-rate');

    // Daily Tadabbur
    Route::get('/tadabbur-history', [\App\Http\Controllers\TadabburController::class, 'index'])->name('tadabbur.index');
    Route::post('/daily-tadabbur/generate-summary', [\App\Http\Controllers\TadabburController::class, 'generateSummary'])->name('daily-tadabbur.generate-summary');
    Route::post('/daily-tadabbur/{dailyTadabbur}', [\App\Http\Controllers\TadabburController::class, 'store'])->name('daily-tadabbur.store');

    // Fasting Plans
    Route::resource('fasting-plans', FastingPlanController::class);
    
    // Smart Schedules (Interactive)
    Route::patch('/smart-schedules/{smartSchedule}', [\App\Http\Controllers\SmartScheduleController::class, 'update'])->name('smart-schedules.update');

    // Push Notifications
    Route::post('/push/subscribe', [\App\Http\Controllers\PushController::class, 'store'])->name('push.subscribe');
    Route::post('/push/test', [\App\Http\Controllers\PushController::class, 'test'])->name('push.test');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
});
// Auth Routes
require __DIR__.'/auth.php';

// Social Auth
Route::controller(App\Http\Controllers\Support\SocialAuthController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
    Route::get('auth/social/complete', 'showCompleteForm')->name('auth.social.complete');
    Route::post('auth/social/complete', 'storeComplete')->name('auth.social.store');
});

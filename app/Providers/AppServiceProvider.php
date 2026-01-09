<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Share Daily Tadabbur to all views using app layout
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $tadabbur = app(\App\Services\TadabburService::class)->getTodayTadabbur(auth()->user());
                $view->with('tadabbur', $tadabbur);
            }
        });

        \App\Models\MenstrualCycle::observe(\App\Observers\MenstrualCycleObserver::class);
        
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\SendHadithOnLogin::class
        );
    }
}

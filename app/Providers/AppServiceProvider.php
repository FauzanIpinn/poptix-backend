<?php

namespace App\Providers;

use App\Models\Cinema;
use App\Observers\CinemaObserver;
use App\Services\BookingService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {
        $this->app->singleton(BookingService::class);
    }

    public function boot(): void {
        Cinema::observe(CinemaObserver::class);

        RateLimiter::for('login-attempts', function (Request $request) {
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        RateLimiter::for('register-attempts', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('booking-attempts', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()->id ?? $request->ip());
        });
    }
}

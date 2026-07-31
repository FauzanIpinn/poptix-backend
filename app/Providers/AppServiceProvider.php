<?php

namespace App\Providers;

use App\Models\Cinema;
use App\Models\User;
use App\Observers\CinemaObserver;
use Illuminate\Support\Facades\Gate;
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
    public function boot(): void {
        Cinema::observe(CinemaObserver::class);
    
        Gate::define('access-admin-panel', function (User $user) {
            return $user->hasRole('admin');
        });
    }
}

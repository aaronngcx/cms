<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // You can also bind any services here if needed
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Facade::clearResolvedInstance('Socialite');
        $this->app->alias('Socialite', \Laravel\Socialite\Facades\Socialite::class);
    }
}

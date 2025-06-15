<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;    // <--- PENTING: Import ini
use Illuminate\Http\Request;                // <--- PENTING: Import ini
use Illuminate\Support\Facades\RateLimiter; // <--- PENTING: Import ini

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
        // Definisikan rate limiter di sini (ini adalah lokasi yang benar dan aman)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limiter khusus untuk login (jika Anda menggunakannya)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
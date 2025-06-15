<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// HAPUS import RateLimiting, Request, RateLimiter jika ada di sini
// use Illuminate\Cache\RateLimiting\Limit;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\RateLimiter;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Konfigurasi Middleware Group 'web'
        $middleware->web(append: [

            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Konfigurasi Middleware Group 'api'
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // HAPUS 'throttle:api' dari sini JIKA ANDA MENGGUNAKANNYA DI routes/api.php
            // Karena definisi rate limiter sudah dipindahkan ke AppServiceProvider.
            // Biarkan kosong di sini.
        ]);

        // Alias Middleware (SANGAT PENTING!)
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // Ini PENTING!
        ]);

        // HAPUS SEMUA DEFINISI RateLimiter::for(...) DARI SINI (jika ada)
        // Contoh:
        // RateLimiter::for('api', function (Request $request) { ... });
        // RateLimiter::for('login', function (Request $request) { ... });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Konfigurasi penanganan pengecualian
    })->create();
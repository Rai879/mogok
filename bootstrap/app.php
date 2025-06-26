<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    channels: __DIR__.'/../routes/channels.php',
    health: '/up',
)->withMiddleware(function (Middleware $middleware) {
    // -------------------------------------------------------------------
    // GRUP MIDDLEWARE 'WEB' - DIKOSONGKAN TOTAL UNTUK DIAGNOSA
    // Ini adalah cara paling agresif untuk menghilangkan semua potensi
    // middleware web yang menyebabkan redirect untuk request API.
    // Jika ini memperbaiki masalah, kita akan tahu masalahnya ada pada
    // salah satu middleware web yang biasanya.
    // -------------------------------------------------------------------
    $middleware->web(append: [
        // KOSONGKAN INI, JANGAN ADA APAPUN DI SINI UNTUK TES
        // Ini akan membuat situs web Anda (jika diakses via browser)
        // mungkin tidak berfungsi dengan benar (misal: sesi, CSRF),
        // tapi ini hanya untuk TEST API.
    ]);

    // GRUP MIDDLEWARE 'API' - Tetap standar untuk Sanctum
    $middleware->api(prepend: [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        // 'throttle:api', // TETAP HAPUS INI jika sudah di routes/api.php
    ]);

    // Alias Middleware - Ini penting, JANGAN diubah
    $middleware->alias([
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ]);

    // HAPUS SEMUA DEFINISI RateLimiter::for(...) DARI SINI
})->withExceptions(function (Exceptions $exceptions) {
    //
})->create();
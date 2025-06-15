// app/Providers/RouteServiceProvider.php

// ...

public function boot(): void
{
    // Hapus atau komentari baris ini jika Anda mendefinisikan RateLimiter di routes/api.php
    // $this->configureRateLimiting();

    // ...
}

// Hapus atau komentari seluruh method ini jika Anda mendefinisikan RateLimiter di routes/api.php
/*
protected function configureRateLimiting(): void
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
}
*/
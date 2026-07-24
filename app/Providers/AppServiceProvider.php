<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('vendor-api', function (Request $request) {
            // Batasi maksimal 10 request per menit per Vendor ID.
            // Jika melebihi ini, Laravel otomatis menolak dengan status 429 Too Many Requests.
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
    }
}

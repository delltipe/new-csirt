<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Add this import
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        RateLimiter::for('login', function (Request $request) {
            $email = $request->input('email');
            $email = is_string($email) ? mb_strtolower(trim($email)) : '';

            return [
                Limit::perMinute(30)->by('login-ip:'.$request->ip()),
                Limit::perMinute(5)->by('login-account:'.$request->ip().'|'.$email),
            ];
        });
        RateLimiter::for('registration', fn (Request $request) => Limit::perMinute(5)->by('registration:'.$request->ip()));
        RateLimiter::for('tracking', fn (Request $request) => Limit::perMinute(30)->by('tracking:'.$request->ip()));
        // Force secure HTTPS asset generation in production environments
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}

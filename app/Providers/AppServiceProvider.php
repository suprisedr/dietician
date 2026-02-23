<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Force HTTPS when running in production or when explicitly requested.
        // On Heroku SSL is terminated at the router and the request to the
        // dyno arrives over HTTP. By trusting proxies (see middleware) and
        // forcing the URL scheme to https we ensure generated URLs and
        // redirects use the correct https scheme.
        if ($this->app->environment('production') || env('FORCE_HTTPS') || env('HEROKU')) {
            URL::forceScheme('https');
        }
    }
}

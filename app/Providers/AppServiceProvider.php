<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override FatSecret singleton to ensure correct credentials are loaded
        $this->app->singleton(\Braunson\FatSecret\FatSecret::class, function () {
            return new \Braunson\FatSecret\FatSecret(
                config('services.fatsecret.key'),
                config('services.fatsecret.secret')
            );
        });
        $this->app->alias(\Braunson\FatSecret\FatSecret::class, 'fatsecret');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers()->symbols());
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

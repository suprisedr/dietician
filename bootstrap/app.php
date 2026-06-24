<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Fix for Heroku HTTPS load balancers
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'device.limit'   => \App\Http\Middleware\EnforceDeviceLimit::class,
            'plan'           => \App\Http\Middleware\RequiresPlan::class,
            'admin.approved' => \App\Http\Middleware\RequireAdminApproval::class,
            'two-factor'     => \App\Http\Middleware\RequireTwoFactorSetup::class,
            'onboarding'     => \App\Http\Middleware\EnsureOnboardingComplete::class,
        ]);

        // Apply device tracking + enforcement to all auth web routes
        $middleware->appendToGroup('web', \App\Http\Middleware\EnforceDeviceLimit::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\RequireAdminApproval::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\EnsureOnboardingComplete::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (TokenMismatchException $e) {
            return redirect()->route('login')->with('status', 'Your session has expired. Please log in again.');
        });
    })->create();

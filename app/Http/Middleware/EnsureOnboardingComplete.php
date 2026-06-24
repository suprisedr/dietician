<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->hasCompletedOnboarding()) {
            if (! $request->routeIs('onboarding.*', 'onboarding.skip', 'logout', 'two-factor.*')) {
                return redirect()->route('onboarding.show', ['step' => $user->onboarding_step]);
            }
        }

        return $next($request);
    }
}

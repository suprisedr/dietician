<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactorSetup
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $request->routeIs('two-factor.*')) {
            return $next($request);
        }

        if ($user->hasTwoFactorEnabled()) {
            if ($request->session()->has('auth.two_factor_passed')) {
                return $next($request);
            }

            return redirect()->route('two-factor.challenge');
        }

        if ($request->session()->get('auth.two_factor_skip_granted') && $user->canSkipTwoFactorSetup()) {
            return $next($request);
        }

        return redirect()->route('two-factor.setup');
    }
}

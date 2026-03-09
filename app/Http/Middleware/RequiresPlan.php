<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforce a minimum subscription plan tier for a route or group.
 *
 * Usage in routes:  ->middleware('plan:package_1')
 *
 * If the authenticated user's plan is below the required tier, they are
 * redirected to the dedicated "feature locked" page so they can upgrade.
 * The required plan slug is flashed to the session so the locked page can
 * display the correct upgrade CTA.
 */
class RequiresPlan
{
    public function handle(Request $request, Closure $next, string $minSlug): Response
    {
        $user = $request->user();

        if (! $user || ! $user->canAccessPlan($minSlug)) {
            // Flash the minimum required plan so the locked page can build the
            // correct "Upgrade to Package X" button.
            return redirect()
                ->route('plan.locked', ['required' => $minSlug])
                ->with('required_plan', $minSlug);
        }

        return $next($request);
    }
}

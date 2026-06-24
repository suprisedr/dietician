<?php

namespace App\Http\Middleware;

use App\Models\PricingPackage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class RequiresPlan
{
    public function handle(Request $request, Closure $next, string $minSlug): Response
    {
        $user = $request->user();
        $hasAccess = $user && $user->canAccessPlan($minSlug);

        if (! $hasAccess) {
            $package = PricingPackage::where('slug', $minSlug)->first();

            View::share('upgradeRequired', [
                'slug'        => $minSlug,
                'planName'    => $package?->name ?? ucfirst(str_replace('_', ' ', $minSlug)),
                'name'        => $package?->name ?? ucfirst(str_replace('_', ' ', $minSlug)),
                'price'       => $package ? 'R' . number_format($package->price_zar) . '/month' : '',
                'isFree'      => $package ? $package->price_zar === 0 : false,
                'features'    => $package?->features ?? [],
                'checkoutUrl' => $package ? route('subscription.checkout', $minSlug) . '?return_to=' . urlencode($request->url()) : route('billing'),
            ]);

            if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
                if ($request->expectsJson()) {
                    return response()->json(['upgrade_required' => $minSlug], 402);
                }

                return redirect()->back()->with('upgrade_required', $minSlug);
            }
        }

        return $next($request);
    }
}

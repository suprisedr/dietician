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

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->canAccessPlan($minSlug)) {
            $package = PricingPackage::where('slug', $minSlug)->first();
            $priceZar = $package?->price_zar ?? 0;

            View::share('upgradeRequired', [
                'slug'        => $minSlug,
                'planName'    => $package?->name ?? ucfirst(str_replace('_', ' ', $minSlug)),
                'name'        => $package?->name ?? ucfirst(str_replace('_', ' ', $minSlug)),
                'price'       => $priceZar > 0 ? 'R' . number_format($priceZar) . '/month' : '',
                'isFree'      => $priceZar == 0,
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

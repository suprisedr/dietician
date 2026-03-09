<?php

namespace App\Http\Controllers;

use App\Models\PricingPackage;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptions) {}

    /**
     * Billing overview page — current plan, subscription status, upgrade options.
     */
    public function billing(Request $request): View
    {
        $user         = $request->user()->load('subscription', 'pricingPackage');
        $packages     = PricingPackage::active()->where('slug', '!=', 'free')->get();
        $subscription = $user->subscription;

        return view('subscription.billing', compact('user', 'packages', 'subscription'));
    }

    /**
     * Redirect the user to Paystack to pay for a package.
     */
    public function checkout(Request $request, string $slug): RedirectResponse
    {
        $package = PricingPackage::where('slug', $slug)
            ->where('is_active', true)
            ->where('price_zar', '>', 0)
            ->firstOrFail();

        $url = $this->subscriptions->initiateCheckout($request->user(), $package);

        return redirect($url);
    }

    /**
     * Paystack redirects here after payment.
     */
    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->query('reference') ?? $request->query('trxref');

        if (! $reference) {
            return redirect()->route('billing')
                ->withErrors(['payment' => 'No payment reference found.']);
        }

        try {
            $subscription = $this->subscriptions->handleCallback($reference);

            return redirect()->route('billing')
                ->with('success', 'Subscription activated! You are now on ' . $subscription->package->name . '.');
        } catch (\Throwable $e) {
            return redirect()->route('billing')
                ->withErrors(['payment' => 'Payment could not be verified: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel the authenticated user's active subscription.
     */
    public function cancel(Request $request): RedirectResponse
    {
        $subscription = $request->user()->subscription;

        if (! $subscription || ! $subscription->isActive()) {
            return redirect()->route('billing')
                ->withErrors(['subscription' => 'No active subscription to cancel.']);
        }

        try {
            $this->subscriptions->cancel($subscription);

            return redirect()->route('billing')
                ->with('success', 'Your subscription has been set to cancel at the end of the billing period.');
        } catch (\Throwable $e) {
            return redirect()->route('billing')
                ->withErrors(['subscription' => 'Could not cancel subscription: ' . $e->getMessage()]);
        }
    }
}

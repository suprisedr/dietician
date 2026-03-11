<?php

namespace App\Services;

use App\Models\PricingPackage;
use App\Models\Subscription;
use App\Models\User;
use Chainbook\Paystack\Facades\Paystack;
use Illuminate\Support\Str;

class SubscriptionService
{
    // ── Initialise a payment ─────────────────────────────────────────────────

    /**
     * Create a Paystack transaction for subscribing to a package.
     * Returns the authorization URL to redirect the user to.
     */
    public function initiateCheckout(User $user, PricingPackage $package): string
    {
        $reference = 'mn_' . Str::upper(Str::random(12)) . '_' . time();

        $payload = [
            'email'        => $user->email,
            'reference'    => $reference,
            'callback_url' => route('subscription.callback'),
            'metadata'     => [
                'user_id'              => $user->id,
                'pricing_package_slug' => $package->slug,
                'cancel_action'        => route('billing'),
            ],
        ];

        if ($package->paystack_plan_code) {
            // Recurring plan — pass the plan code AND the matching amount in
            // kobo. Paystack requires amount to equal the plan's defined amount.
            $payload['plan']   = $package->paystack_plan_code;
            $payload['amount'] = $package->paystack_amount ?: ($package->price_zar * 100);
        } else {
            // One-off / free upgrade — pass amount in kobo only
            $payload['amount'] = $package->price_zar * 100;
        }

        $response = Paystack::initializeTransaction($payload);

        if (! ($response['status'] ?? false)) {
            throw new \RuntimeException('Paystack initialisation failed: ' . ($response['message'] ?? 'Unknown error'));
        }

        return $response['data']['authorization_url'];
    }

    // ── Verify & activate after callback ─────────────────────────────────────

    /**
     * Called when the user returns from Paystack payment page.
     * Verifies the transaction and creates/updates the subscription record.
     */
    public function handleCallback(string $reference): Subscription
    {
        $response = Paystack::verifyTransaction($reference);

        if (
            ! ($response['status'] ?? false) ||
            ($response['data']['status'] ?? '') !== 'success'
        ) {
            throw new \RuntimeException('Payment verification failed.');
        }

        $data     = $response['data'];
        $metadata = $data['metadata'] ?? [];
        $userId   = $metadata['user_id'] ?? null;
        $slug     = $metadata['pricing_package_slug'] ?? null;

        $user    = User::findOrFail($userId);
        $package = PricingPackage::where('slug', $slug)->firstOrFail();

        // Create or update the subscription record
        $subscription = Subscription::updateOrCreate(
            ['user_id' => $user->id, 'pricing_package_slug' => $slug],
            [
                'paystack_customer_code'      => $data['customer']['customer_code'] ?? null,
                'paystack_authorization_code' => $data['authorization']['authorization_code'] ?? null,
                'status'                      => 'active',
                'amount_zar'                  => (int) $data['amount'] / 100,
                'next_payment_at'             => now()->addMonth(),
                'last_event_payload'          => $data,
            ]
        );

        // Upgrade the user's plan
        $user->update(['pricing_package_slug' => $slug]);

        return $subscription;
    }

    // ── Webhook events ───────────────────────────────────────────────────────

    /**
     * Process a verified Paystack webhook event payload.
     */
    public function handleWebhookEvent(array $event): void
    {
        $eventType = $event['event'] ?? null;
        $data      = $event['data'] ?? [];

        match ($eventType) {
            'subscription.create'          => $this->onSubscriptionCreated($data),
            'subscription.disable'         => $this->onSubscriptionDisabled($data),
            'subscription.not_renew'       => $this->onSubscriptionNotRenew($data),
            'charge.success'               => $this->onChargeSuccess($data),
            'invoice.payment_failed'       => $this->onInvoiceFailed($data),
            default                        => null,
        };
    }

    private function onSubscriptionCreated(array $data): void
    {
        $subscription = $this->findBySubscriptionCode($data['subscription_code'] ?? null)
            ?? $this->findByCustomerCode($data['customer']['customer_code'] ?? null);

        if (! $subscription) {
            return;
        }

        $subscription->update([
            'paystack_subscription_code' => $data['subscription_code'] ?? $subscription->paystack_subscription_code,
            'paystack_plan_code'         => $data['plan']['plan_code'] ?? $subscription->paystack_plan_code,
            'paystack_email_token'       => $data['email_token'] ?? null,
            'status'                     => 'active',
            'next_payment_at'            => isset($data['next_payment_date'])
                ? \Carbon\Carbon::parse($data['next_payment_date'])
                : $subscription->next_payment_at,
            'last_event_payload'         => $data,
        ]);

        $subscription->user->update(['pricing_package_slug' => $subscription->pricing_package_slug]);
    }

    private function onSubscriptionDisabled(array $data): void
    {
        $subscription = $this->findBySubscriptionCode($data['subscription_code'] ?? null);

        if (! $subscription) {
            return;
        }

        $subscription->update([
            'status'             => 'cancelled',
            'cancelled_at'       => now(),
            'ends_at'            => isset($data['cancelledAt'])
                ? \Carbon\Carbon::parse($data['cancelledAt'])
                : now()->endOfMonth(),
            'last_event_payload' => $data,
        ]);
    }

    private function onSubscriptionNotRenew(array $data): void
    {
        $subscription = $this->findBySubscriptionCode($data['subscription_code'] ?? null);

        if (! $subscription) {
            return;
        }

        $subscription->update([
            'status'             => 'non_renewing',
            'last_event_payload' => $data,
        ]);
    }

    private function onChargeSuccess(array $data): void
    {
        // Renewal charge succeeded — push next_payment_at forward
        $subCode = $data['subscription_code'] ?? null;

        if (! $subCode) {
            return;
        }

        $subscription = $this->findBySubscriptionCode($subCode);

        if (! $subscription) {
            return;
        }

        $subscription->update([
            'status'             => 'active',
            'next_payment_at'    => now()->addMonth(),
            'last_event_payload' => $data,
        ]);
    }

    private function onInvoiceFailed(array $data): void
    {
        $subscription = $this->findBySubscriptionCode($data['subscription']['subscription_code'] ?? null);

        if (! $subscription) {
            return;
        }

        $subscription->update([
            'status'             => 'expired',
            'last_event_payload' => $data,
        ]);

        // Downgrade owner to free
        $subscription->user->update(['pricing_package_slug' => 'free']);

        // Also downgrade all team members to free
        $subscription->user->teamMembers()->update(['pricing_package_slug' => 'free']);
    }

    // ── Cancel ───────────────────────────────────────────────────────────────

    /**
     * Cancel a subscription via Paystack API.
     */
    public function cancel(Subscription $subscription): void
    {
        if ($subscription->paystack_subscription_code && $subscription->paystack_email_token) {
            Paystack::disableSubscription([
                'code'  => $subscription->paystack_subscription_code,
                'token' => $subscription->paystack_email_token,
            ]);
        }

        $subscription->update([
            'status'       => 'non_renewing',
            'cancelled_at' => now(),
        ]);
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function findBySubscriptionCode(?string $code): ?Subscription
    {
        if (! $code) {
            return null;
        }
        return Subscription::where('paystack_subscription_code', $code)->first();
    }

    private function findByCustomerCode(?string $code): ?Subscription
    {
        if (! $code) {
            return null;
        }
        return Subscription::where('paystack_customer_code', $code)
            ->latest()
            ->first();
    }
}

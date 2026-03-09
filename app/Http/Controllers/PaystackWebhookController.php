<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaystackWebhookController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptions) {}

    public function handle(Request $request): Response
    {
        // ── 1. Verify signature ──────────────────────────────────────────────
        $signature = $request->header('X-Paystack-Signature');
        $secret    = config('paystack.secret_key');
        $computed  = hash_hmac('sha512', $request->getContent(), $secret);

        if (! hash_equals($computed, $signature ?? '')) {
            return response('Unauthorized', 401);
        }

        // ── 2. Dispatch event ────────────────────────────────────────────────
        $event = $request->json()->all();

        try {
            $this->subscriptions->handleWebhookEvent($event);
        } catch (\Throwable $e) {
            // Log but always return 200 so Paystack doesn't keep retrying
            logger()->error('Paystack webhook error: ' . $e->getMessage(), [
                'event' => $event['event'] ?? null,
            ]);
        }

        return response('OK', 200);
    }
}

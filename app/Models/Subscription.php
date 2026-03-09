<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'pricing_package_slug',
        'paystack_subscription_code',
        'paystack_customer_code',
        'paystack_plan_code',
        'paystack_email_token',
        'paystack_authorization_code',
        'status',
        'amount_zar',
        'trial_ends_at',
        'next_payment_at',
        'cancelled_at',
        'ends_at',
        'last_event_payload',
    ];

    protected $casts = [
        'trial_ends_at'      => 'datetime',
        'next_payment_at'    => 'datetime',
        'cancelled_at'       => 'datetime',
        'ends_at'            => 'datetime',
        'last_event_payload' => 'array',
        'amount_zar'         => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(PricingPackage::class, 'pricing_package_slug', 'slug');
    }

    // ── Status helpers ───────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'non_renewing']);
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }
        return $this->ends_at && $this->ends_at->isPast();
    }

    /** Human-readable status label. */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'active'        => 'Active',
            'non_renewing'  => 'Cancels at period end',
            'cancelled'     => 'Cancelled',
            'expired'       => 'Expired',
            'pending'       => 'Pending',
            default         => ucfirst($this->status),
        };
    }

    /** CSS colour hint for status badge. */
    public function statusStyle(): string
    {
        return match ($this->status) {
            'active'       => 'success',
            'non_renewing' => 'warning',
            'cancelled'    => 'danger',
            'expired'      => 'danger',
            default        => 'muted',
        };
    }
}

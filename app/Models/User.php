<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'dietician_number',
        'password',
        'pricing_package_slug',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    public function pricingPackage()
    {
        return $this->belongsTo(PricingPackage::class, 'pricing_package_slug', 'slug');
    }

    /** The user's most recent subscription record. */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Max devices allowed by the user's current package. */
    public function deviceLimit(): int
    {
        return $this->pricingPackage?->max_users ?? 1;
    }

    /** True when the user has an active or non-renewing paid subscription. */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription?->isActive() ?? false;
    }

    /** True when the user is on the free tier. */
    public function onFreePlan(): bool
    {
        return $this->pricing_package_slug === 'free' || ! $this->hasActiveSubscription();
    }

    /**
     * The sort_order of the user's current package (1=free, 2=package_1, 3=package_2, 4=package_3).
     * Falls back to 1 so users without a package can never access gated features.
     */
    public function planTier(): int
    {
        return $this->pricingPackage?->sort_order ?? 1;
    }

    /**
     * Returns true when the user's plan tier is >= the minimum required tier
     * for the given package slug (e.g. 'package_1', 'package_2', 'package_3').
     */
    public function canAccessPlan(string $minSlug): bool
    {
        $required = PricingPackage::where('slug', $minSlug)->value('sort_order') ?? 999;
        return $this->planTier() >= $required;
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'dietician_number',
        'letterhead_path',
        'password',
        'pricing_package_slug',
        'owner_id',
        'admin_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'two_factor_prompted_at',
        'reminder_send_day',
        'reminder_send_hour',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'admin_verified_at'  => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_prompted_at' => 'datetime',
            'two_factor_recovery_codes' => 'array',
            'password'             => 'hashed',
            'reminder_send_day'   => 'integer',
            'reminder_send_hour'  => 'integer',
        ];
    }

    public function isAdminVerified(): bool
    {
        return $this->admin_verified_at !== null;
    }

    public function letterheadBase64(): ?string
    {
        if (! $this->letterhead_path) return null;
        $fullPath = Storage::disk('local')->path($this->letterhead_path);
        if (! file_exists($fullPath)) return null;
        $mime = mime_content_type($fullPath);
        $data = base64_encode(file_get_contents($fullPath));
        return "data:{$mime};base64,{$data}";
    }

    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_secret) && ! is_null($this->two_factor_confirmed_at);
    }

    public function twoFactorGracePeriodEndsAt(): ?Carbon
    {
        return $this->two_factor_prompted_at?->copy()->addDays(15);
    }

    public function canSkipTwoFactorSetup(): bool
    {
        if ($this->hasTwoFactorEnabled()) {
            return false;
        }

        if (is_null($this->two_factor_prompted_at)) {
            return true;
        }

        return now()->lt($this->twoFactorGracePeriodEndsAt());
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    public function pricingPackage(): BelongsTo
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

    /**
     * The owner who invited this user (null if this user owns their own subscription).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Users that this user has invited (and who accepted).
     */
    public function teamMembers(): HasMany
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    /**
     * Pending and accepted invitations sent by this user.
     */
    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class, 'owner_id');
    }

    // ── Team helpers ──────────────────────────────────────────────────────────

    /**
     * True if this user was invited by someone else (not a subscription owner).
     */
    public function isTeamMember(): bool
    {
        return ! is_null($this->owner_id);
    }

    /**
     * True if this user owns their own subscription (not invited).
     */
    public function isSubscriptionOwner(): bool
    {
        return is_null($this->owner_id);
    }

    /**
     * The user whose subscription governs this user's access.
     * For invited members, that is their owner. For owners, it's themselves.
     */
    public function subscriptionOwner(): self
    {
        return $this->owner ?? $this;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Max devices allowed by the subscription owner's current package. */
    public function deviceLimit(): int
    {
        return $this->subscriptionOwner()->pricingPackage?->max_users ?? 1;
    }

    /** True when the subscription owner has an active or non-renewing paid subscription. */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptionOwner()->subscription?->isActive() ?? false;
    }

    /** True when the user is on the free tier. */
    public function onFreePlan(): bool
    {
        $owner = $this->subscriptionOwner();
        return $owner->pricing_package_slug === 'free' || ! $this->hasActiveSubscription();
    }

    /**
     * The sort_order of the governing package (1=free … 4=package_3).
     * Always resolved via the subscription owner.
     */
    public function planTier(): int
    {
        return $this->subscriptionOwner()->pricingPackage?->sort_order ?? 1;
    }

    /**
     * Returns true when the effective plan tier is >= the minimum required tier.
     */
    public function canAccessPlan(string $minSlug): bool
    {
        $required = PricingPackage::where('slug', $minSlug)->value('sort_order') ?? 999;
        return $this->planTier() >= $required;
    }

    /**
     * How many more members this user can still invite (0 if at limit or if they
     * are themselves a member and therefore cannot invite anyone).
     */
    public function remainingInviteSlots(): int
    {
        if ($this->isTeamMember()) {
            return 0;
        }

        $limit     = $this->pricingPackage?->max_users ?? 1;
        $usedSlots = $this->teamMembers()->count() + 1; // +1 for the owner
        return max(0, $limit - $usedSlots);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPackage extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'badge_label',
        'badge_style',
        'price_zar',
        'tagline',
        'is_featured',
        'is_active',
        'max_users',
        'group_price_zar',
        'sort_order',
        'features',
        'paystack_plan_code',
        'paystack_amount',
    ];

    protected $casts = [
        'features'         => 'array',
        'is_featured'      => 'boolean',
        'is_active'        => 'boolean',
        'price_zar'        => 'integer',
        'group_price_zar'  => 'integer',
        'max_users'        => 'integer',
        'sort_order'       => 'integer',
        'paystack_amount'  => 'integer',
    ];

    /** Return only active packages in display order. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** Formatted price string, e.g. "R 499 / month" or "Free". */
    public function formattedPrice(): string
    {
        if ($this->price_zar === 0) {
            return 'Free';
        }

        return 'R ' . number_format($this->price_zar) . ' / month';
    }
}

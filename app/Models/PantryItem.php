<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PantryItem extends Model
{
    protected $fillable = [
        'user_id',
        'storage_type',
        'item',
        'quantity',
        'date_added',
        'expiry_date',
        'notes',
    ];

    protected $casts = [
        'date_added'  => 'date',
        'expiry_date' => 'date',
    ];

    const TYPES = ['pantry', 'freezer'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

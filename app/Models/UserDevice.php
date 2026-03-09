<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDevice extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'device_name',
        'browser',
        'platform',
        'ip_address',
        'last_active_at',
        'logged_in_at',
        'is_current',
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
        'logged_in_at'   => 'datetime',
        'is_current'     => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Human-readable "last seen" string.
     */
    public function lastSeenLabel(): string
    {
        if (! $this->last_active_at) {
            return 'Just now';
        }

        return $this->last_active_at->diffForHumans();
    }
}

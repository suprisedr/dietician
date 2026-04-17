<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodDiary extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'diary_date',
        'breakfast',
        'snack1',
        'lunch',
        'snack2',
        'supper',
        'snack3',
        'rating',
        'improvement',
        'patient_token',
        'submitted_at',
    ];

    protected $casts = [
        'diary_date'   => 'date',
        'rating'       => 'integer',
        'submitted_at' => 'datetime',
    ];

    const SLOTS = [
        'breakfast' => 'Breakfast',
        'snack1'    => 'Snack',
        'lunch'     => 'Lunch',
        'snack2'    => 'Snack',
        'supper'    => 'Supper',
        'snack3'    => 'Snack',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}

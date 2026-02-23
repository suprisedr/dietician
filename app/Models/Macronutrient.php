<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Macronutrient extends Model
{
    protected $fillable = [
        'patient_id',
        'type',
        'range_min',
        'range_max',
        'selected_percentage',
        'kj',
        'grams',
    ];

    protected $casts = [
        'range_min' => 'float',
        'range_max' => 'float',
        'selected_percentage' => 'float',
        'kj' => 'float',
        'grams' => 'float',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}

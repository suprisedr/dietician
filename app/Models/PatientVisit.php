<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientVisit extends Model
{
    protected $fillable = [
        'patient_id',
        'visited_at',
        'weight',
        'height',
        'notes',
        'oedema',
        'oedema_changed_at',
    ];

    protected $casts = [
        'visited_at'        => 'datetime',
        'weight'            => 'decimal:2',
        'height'            => 'decimal:2',
        'oedema'            => 'boolean',
        'oedema_changed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * BMI computed from this visit's weight and height (or patient's height as fallback).
     */
    public function getBmiAttribute(): ?float
    {
        $h = $this->height ?? optional($this->patient)->height;
        if (! $this->weight || ! $h || $h <= 0) {
            return null;
        }
        $hm = $h / 100;
        return round($this->weight / ($hm * $hm), 2);
    }
}

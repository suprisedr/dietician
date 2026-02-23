<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'age',
        'gender',
        'weight',
        'height',
        'activity_factor',
        'exchange_template_id',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'activity_factor' => 'float',
    ];

    // Accessor to calculate BMR using Mifflin-St Jeor Equation
    public function getBmrAttribute()
    {
        if ($this->gender === 'male') {
            return (10 * $this->weight) + (6.25 * $this->height) - (5 * $this->age) + 5;
        } elseif ($this->gender === 'female') {
            return (10 * $this->weight) + (6.25 * $this->height) - (5 * $this->age) - 161;
        }
        return null; // In case gender is not set or invalid
    }

    // Accessor to calculate BMI
    public function getBmiAttribute()
    {
        if ($this->height > 0) {
            $heightInMeters = $this->height / 100;
            return $this->weight / ($heightInMeters * $heightInMeters);
        }
        return null;
    }

    // Accessor to calculate IBW (Ideal Body Weight) using metric formula
    public function getIbwAttribute()
    {
        if ($this->gender === 'male') {
            return 50 + (0.9 * ($this->height - 150));
        } elseif ($this->gender === 'female') {
            return 45 + (0.9 * ($this->height - 150));
        }
        return null;
    }

    // Accessor to calculate ABW (Adjusted Body Weight)
    public function getAbwAttribute()
    {
        $ibw = $this->ibw;
        if ($ibw && $this->weight > $ibw) {
            return $ibw + (0.4 * ($this->weight - $ibw));
        }
        return $this->weight; // If weight <= IBW, ABW = actual weight
    }

    // Accessor to calculate BMI category based on South African/WHO standards
    public function getBmiCategoryAttribute()
    {
        $bmi = $this->bmi;
        if (!$bmi) return 'N/A';

        if ($bmi < 18.5) return 'Underweight';
        if ($bmi < 25) return 'Normal';
        if ($bmi < 30) return 'Overweight';
        return 'Obese';
    }

    // Accessor for RMR (Resting Metabolic Rate) - using same as BMR
    public function getRmrAttribute()
    {
        return $this->bmr;
    }

    // Accessor for AF (Activity Factor) - returns stored value
    public function getAfAttribute()
    {
        return $this->activity_factor;
    }

    // Accessor for TEE (Total Energy Expenditure)
    public function getTeeAttribute()
    {
        $bmr = $this->bmr;
        $af = $this->activity_factor;
        return $bmr && $af ? $bmr * $af : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function macronutrients(): HasMany
    {
        return $this->hasMany(Macronutrient::class);
    }

    public function exchangeTemplate(): BelongsTo
    {
        return $this->belongsTo(\App\Models\ExchangeTemplate::class, 'exchange_template_id');
    }
}

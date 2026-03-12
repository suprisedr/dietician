<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'name',
        'surname',
        'reason_for_assessment',
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

    /**
     * Full display name including title and surname.
     */
    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->title,
            $this->name,
            $this->surname,
        ])));
    }

    /**
     * Mifflin-St Jeor helper — computes BMR in kcal/day using a given weight (kg).
     */
    private function mifflinStJeor(float $weightKg): ?float
    {
        if ($this->gender === 'male') {
            return (10 * $weightKg) + (6.25 * $this->height) - (5 * $this->age) + 5;
        } elseif ($this->gender === 'female') {
            return (10 * $weightKg) + (6.25 * $this->height) - (5 * $this->age) - 161;
        }
        return null;
    }

    /**
     * For obese patients (BMI > 30) the Mifflin-St Jeor equation overestimates
     * energy needs when actual body weight is used. This accessor returns the
     * weight that will be plugged into the equation:
     *
     *  - BMI ≤ 30 → actual weight
     *  - BMI > 30 → Obesity-adjusted weight: IBW + 0.25 × (actual − IBW)
     *
     * Note: the 0.25 factor here is specifically for BMR estimation; it differs
     * from the 0.4 factor used in the general clinical ABW accessor (getAbwAttribute).
     */
    public function getWeightForBmrAttribute(): ?float
    {
        $bmi = $this->bmi;
        $ibw = $this->ibw;

        if ($bmi && $bmi > 30 && $ibw && $this->weight > $ibw) {
            return $ibw + 0.25 * ((float) $this->weight - $ibw);
        }

        return (float) $this->weight;
    }

    /**
     * Accessor to calculate BMR using Mifflin-St Jeor Equation.
     *
     * When BMI > 30 the obesity-adjusted body weight (IBW + 0.25 × excess) is
     * substituted for actual weight to avoid overestimating energy needs.
     * Returns kcal/day (the rest of the application converts to kJ via × 4.184).
     */
    public function getBmrAttribute(): ?float
    {
        $weight = $this->weight_for_bmr;
        return $weight !== null ? $this->mifflinStJeor($weight) : null;
    }

    /**
     * BMR calculated with actual body weight (no obesity correction).
     * Useful for displaying the "uncorrected" value alongside adjusted estimates.
     */
    public function getBmrActualAttribute(): ?float
    {
        return $this->mifflinStJeor((float) $this->weight);
    }

    /**
     * BMR using the BMI-adjusted weight cap method:
     * The weight entered into Mifflin-St Jeor is capped at the weight
     * corresponding to BMI 25 kg/m²  (i.e. 25 × height² in metres).
     * Only differs from getBmrAttribute when BMI > 25.
     */
    public function getBmrBmiAdjustedAttribute(): ?float
    {
        if (!$this->height || $this->height <= 0) {
            return null;
        }
        $heightM      = $this->height / 100;
        $cappedWeight = 25 * $heightM * $heightM;           // weight at BMI = 25
        $weight       = min((float) $this->weight, $cappedWeight);
        return $this->mifflinStJeor($weight);
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

    // Accessor to calculate IBW (Ideal Body Weight) using the BMI method:
    // Target a BMI of 22 (midpoint of the healthy 18.5–25 range).
    // Formula: IBW (kg) = targetBMI × height² (m)
    public function getIbwAttribute()
    {
        if (! $this->height || $this->height <= 0) {
            return null;
        }

        $heightM   = $this->height / 100;  // cm → m
        $targetBMI = 22;                   // midpoint of healthy BMI range

        return round($targetBMI * ($heightM ** 2), 2);
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

    public function visits(): HasMany
    {
        return $this->hasMany(PatientVisit::class)->orderByDesc('visited_at');
    }
}

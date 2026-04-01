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
        'email',
        'id_type',
        'id_number',
        'date_of_birth',
        'address',
        'reason_for_assessment',
        'age',
        'gender',
        'weight',
        'height',
        'activity_factor',
        'ibw_bmi_target',
        'use_ibw_weight',
        'exchange_template_id',
        'diet_preset_id',
    ];

    protected $casts = [
        'weight'          => 'decimal:2',
        'height'          => 'decimal:2',
        'activity_factor' => 'float',
        'ibw_bmi_target'  => 'integer',
        'use_ibw_weight'  => 'boolean',
        'date_of_birth'   => 'date',
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
            return (10 * $weightKg) + (6.25 * $this->height) - (4.92 * $this->age) + 5;
        } elseif ($this->gender === 'female') {
            return (10 * $weightKg) + (6.25 * $this->height) - (4.92 * $this->age) - 161;
        }
        return null;
    }

    /**
     * Selects the appropriate weight for the Mifflin-St Jeor BMR equation based
     * on clinical BMI category:
     *
     *  - BMI < 18.5 (Underweight) → Actual Body Weight (ABW)
     *  - BMI 18.5–29.9 (Normal / Overweight) → Actual Body Weight (ABW)
     *  - BMI ≥ 30 (Obese) → Adjusted Body Weight: IBW + 0.4 × (actual − IBW)
     *
     * Using ABW in obese patients overestimates energy needs; AdjBW with the
     * 0.4 correction factor accounts for the lower metabolic activity of excess
     * adipose tissue.
     */
    public function getWeightForBmrAttribute(): ?float
    {
        // When IBW is explicitly activated, use the IBW value directly for all
        // energy calculations instead of actual body weight.
        if ($this->use_ibw_weight) {
            return $this->ibw ?? (float) $this->weight;
        }

        $bmi = $this->bmi;
        $ibw = $this->ibw;

        // BMI ≥ 30: use Adjusted Body Weight (AdjBW = IBW + 0.4 × (actual − IBW))
        if ($bmi && $bmi >= 30 && $ibw && $this->weight > $ibw) {
            return $ibw + 0.4 * ((float) $this->weight - $ibw);
        }

        return (float) $this->weight;
    }

    /**
     * Accessor to calculate BMR using Mifflin-St Jeor Equation.
     *
     * When BMI >= 30 the adjusted body weight (IBW + 0.4 × (actual − IBW)) is
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

    // Accessor to calculate BMI  →  BMI = weight (kg) ÷ height (m)²
    public function getBmiAttribute(): ?float
    {
        if ($this->height > 0 && $this->weight > 0) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }
        return null;
    }

    /**
     * Generic IBW helper: Target Weight = BMI_target × height(m)²
     */
    private function ibwForBmi(float $targetBmi): ?float
    {
        if (! $this->height || $this->height <= 0) {
            return null;
        }
        $hm = $this->height / 100;
        return round($targetBmi * ($hm ** 2), 2);
    }

    /**
     * IBW using the dietitian's chosen BMI target (22, 25, or 30).
     * Falls back to 22 when no preference is stored.
     */
    public function getIbwAttribute(): ?float
    {
        return $this->ibwForBmi((float) ($this->ibw_bmi_target ?? 22));
    }

    /** IBW at BMI 22 — always at BMI 22 (for reference display) */
    public function getIbw22Attribute(): ?float
    {
        return $this->ibwForBmi(22);
    }

    /** IBW at BMI 25 — upper limit of healthy range */
    public function getIbw25Attribute(): ?float
    {
        return $this->ibwForBmi(25);
    }

    /** IBW at BMI 30 — obesity threshold */
    public function getIbw30Attribute(): ?float
    {
        return $this->ibwForBmi(30);
    }

    // Accessor to calculate ABW (Adjusted Body Weight) — AdjBW = IBW + 0.4 × (actual − IBW)
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

    // Accessor for RMR (Resting Metabolic Rate) - kcal/day via Mifflin-St Jeor
    public function getRmrAttribute()
    {
        return $this->bmr; // kcal/day
    }

    // Accessor for AF (Activity Factor) - returns stored value
    public function getAfAttribute()
    {
        return $this->activity_factor;
    }

    /**
     * TEE (Total Energy Expenditure) in kJ/day.
     *
     * Male RMR   = 10W + 6.25H − 5A + 5    (kcal)
     * Female RMR = 10W + 6.25H − 5A − 161  (kcal)
     * TEE_kJ     = RMR_kcal × Activity Factor × 4.184
     */
    public function getTeeAttribute(): ?float
    {
        $rmr = $this->bmr;  // kcal/day (gender-specific Mifflin-St Jeor)
        $af  = $this->activity_factor;
        if (! $rmr || ! $af) {
            return null;
        }
        return $rmr * $af * 4.184; // kJ/day
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

    public function dietPreset(): BelongsTo
    {
        return $this->belongsTo(\App\Models\DietPreset::class, 'diet_preset_id');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(PatientVisit::class)->orderByDesc('visited_at');
    }
}

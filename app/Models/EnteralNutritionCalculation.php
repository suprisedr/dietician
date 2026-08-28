<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnteralNutritionCalculation extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'label',
        'clinical_condition',
        'weight_type',
        'weight_kg',
        'energy_kcal_per_kg',
        'energy_target_kcal',
        'protein_g_per_kg',
        'protein_target_g',
        'formula_density',
        'feeding_hours_per_day',
        'daily_volume_ml',
        'rate_ml_per_hour',
        'water_flush_ml',
        'water_flush_frequency',
        'notes',
    ];

    protected $casts = [
        'weight_kg'                 => 'decimal:2',
        'energy_kcal_per_kg'        => 'decimal:2',
        'energy_target_kcal'        => 'decimal:1',
        'protein_g_per_kg'          => 'decimal:2',
        'protein_target_g'          => 'decimal:1',
        'formula_density'           => 'decimal:1',
        'feeding_hours_per_day'     => 'integer',
        'daily_volume_ml'           => 'decimal:0',
        'rate_ml_per_hour'          => 'decimal:1',
        'water_flush_ml'            => 'integer',
    ];

    // ── SASPEN / ESPEN condition definitions (SA standards) ───────────────────

    public const CONDITIONS = [
        'standard'              => 'Standard / Maintenance',
        'critically_ill'        => 'Critically Ill (ICU)',
        'post_surgical'         => 'Post-Surgical / Peri-operative',
        'trauma'                => 'Trauma',
        'burns'                 => 'Burns',
        'cancer'                => 'Oncology / Cancer',
        'renal_non_dialysis'    => 'Chronic Renal Failure (Non-dialysis)',
        'renal_dialysis'        => 'Renal Failure (Haemodialysis / CAPD)',
        'hepatic'               => 'Hepatic Disease',
        'copd'                  => 'COPD / Pulmonary Disease',
        'diabetes'              => 'Diabetes / Hyperglycaemia',
        'pressure_injury'       => 'Pressure Injury / Wound Healing',
    ];

    /**
     * Returns recommended [min, max] kcal/kg/day for a clinical condition.
     * Based on SASPEN clinical practice guidelines and ESPEN 2019/2023.
     */
    public static function energyRangeFor(string $condition): array
    {
        return match ($condition) {
            'critically_ill'     => [20, 25],
            'post_surgical'      => [25, 30],
            'trauma'             => [25, 30],
            'burns'              => [30, 35],
            'cancer'             => [25, 30],
            'renal_non_dialysis' => [25, 35],
            'renal_dialysis'     => [30, 35],
            'hepatic'            => [30, 40],
            'copd'               => [25, 30],
            'diabetes'           => [25, 30],
            'pressure_injury'    => [30, 35],
            default              => [25, 30], // standard
        };
    }

    /**
     * Returns recommended [min, max] g protein/kg/day for a clinical condition.
     */
    public static function proteinRangeFor(string $condition): array
    {
        return match ($condition) {
            'critically_ill'     => [1.2, 2.0],
            'post_surgical'      => [1.2, 1.5],
            'trauma'             => [1.5, 2.0],
            'burns'              => [1.5, 2.5],
            'cancer'             => [1.0, 1.5],
            'renal_non_dialysis' => [0.6, 0.8],
            'renal_dialysis'     => [1.2, 1.5],
            'hepatic'            => [1.0, 1.5],
            'copd'               => [1.2, 1.5],
            'diabetes'           => [1.0, 1.5],
            'pressure_injury'    => [1.5, 2.0],
            default              => [0.8, 1.2], // standard
        };
    }

    /**
     * Number of water flushes per day, derived from the flush frequency.
     */
    public static function flushesPerDayFor(?string $frequency): int
    {
        $hours = (int) filter_var($frequency ?? '6-hourly', FILTER_SANITIZE_NUMBER_INT);

        return (int) round(24 / max(1, $hours ?: 6));
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getConditionLabelAttribute(): string
    {
        return self::CONDITIONS[$this->clinical_condition] ?? ucfirst($this->clinical_condition);
    }

    public function getEnergyTargetKjAttribute(): float
    {
        return round($this->energy_target_kcal * 4.184);
    }

    public function getFlushesPerDayAttribute(): int
    {
        return self::flushesPerDayFor($this->water_flush_frequency);
    }

    /**
     * Total water delivered per day by flushes — the only water counted in the feed.
     */
    public function getWaterFlushTotalMlAttribute(): int
    {
        return (int) ($this->water_flush_ml ?? 30) * $this->flushes_per_day;
    }
}

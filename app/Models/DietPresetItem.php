<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DietPresetItem extends Model
{
    protected $fillable = [
        'diet_preset_id', 'name', 'nu',
        'cho_g', 'protein_min_g', 'protein_max_g', 'fat_min_g', 'fat_max_g', 'kj',
        'slot_breakfast', 'slot_snack1', 'slot_lunch',
        'slot_snack2', 'slot_supper', 'slot_snack3',
    ];

    public function preset(): BelongsTo
    {
        return $this->belongsTo(DietPreset::class, 'diet_preset_id');
    }
}

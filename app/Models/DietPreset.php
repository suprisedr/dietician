<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DietPreset extends Model
{
    protected $fillable = ['key', 'name', 'description', 'kcal_target'];

    public function items(): HasMany
    {
        return $this->hasMany(DietPresetItem::class);
    }
}

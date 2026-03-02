<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MealItem extends Model
{
    protected $fillable = [
        'category',
        'name',
        'serving_size',
        'cho_g',
        'protein_g',
        'fat_g',
        'energy_kj',
        'energy_kcal',
        'fruit_veg_portions',
        'is_system',
        'created_by',
    ];

    protected $casts = [
        'cho_g'             => 'float',
        'protein_g'         => 'float',
        'fat_g'             => 'float',
        'energy_kj'         => 'float',
        'energy_kcal'       => 'float',
        'fruit_veg_portions'=> 'integer',
        'is_system'         => 'boolean',
    ];

    /** All items visible to a given user: system + their own custom. */
    public function scopeVisibleTo(Builder $query, int $userId): Builder
    {
        return $query->where('is_system', true)
                     ->orWhere('created_by', $userId);
    }

    public static function categories(): array
    {
        return [
            'Fruit & Vegetables',
            'Starchy Foods',
            'Protein',
            'Milk & Dairy',
            'Spreading Fat, Oil & Sauce',
        ];
    }
}

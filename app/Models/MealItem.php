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
        'fiber_g',
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
        'fiber_g'           => 'float',
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
            'Starch',
            'Protein - Animal',
            'Protein - Plant',
            'Fruit',
            'Vegetable',
            'Dairy',
            'Fat',
            'Other/Limit',
        ];
    }

    /**
     * Map an exchange template item name (e.g. "Starch", "Meat, lean fat")
     * to one or more library categories so the item picker can filter.
     */
    public static function exchangeToLibraryCategories(string $exchangeName): array
    {
        $lower = strtolower(trim($exchangeName));

        if (str_contains($lower, 'starch'))          return ['Starch'];
        if (str_contains($lower, 'fruit'))            return ['Fruit'];
        if (str_contains($lower, 'veg'))              return ['Vegetable'];
        if (str_contains($lower, 'milk'))             return ['Dairy'];
        if (str_contains($lower, 'meat'))             return ['Protein - Animal'];
        if (str_contains($lower, 'plant'))            return ['Protein - Plant'];
        if (str_contains($lower, 'fat'))              return ['Fat'];
        if (str_contains($lower, 'sugar'))            return ['Other/Limit'];
        if (str_contains($lower, 'alcohol'))          return ['Other/Limit'];
        return [];
    }

    /**
     * Reverse: given a library category, return the best-matching library category
     * name. Used when importing FatSecret items from the meal planner.
     */
    public static function libraryCategory(string $exchangeName): string
    {
        $cats = self::exchangeToLibraryCategories($exchangeName);
        return $cats[0] ?? 'Other/Limit';
    }
}

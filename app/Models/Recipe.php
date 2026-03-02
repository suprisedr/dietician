<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'ingredients',
        'directions',
        'category',
        'servings',
        'prep_time_min',
        'cook_time_min',
        'notes',
        'is_system',
    ];

    protected $casts = [
        'is_system'     => 'boolean',
        'servings'      => 'integer',
        'prep_time_min' => 'integer',
        'cook_time_min' => 'integer',
    ];

    const CATEGORIES = [
        'Main Course',
        'Side Dish',
        'Salad',
        'Appetizer',
        'Dessert',
        'Breakfast',
        'Snack',
        'Beverage',
        'Other',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

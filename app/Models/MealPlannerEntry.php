<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealPlannerEntry extends Model
{
    protected $fillable = [
        'week_id',
        'day_of_week',
        'meal_slot',
        'exchange_category',
        'sort_order',
        'meal_text',
        'meal_item_id',
        'notes',
    ];

    public function week(): BelongsTo
    {
        return $this->belongsTo(MealPlannerWeek::class, 'week_id');
    }

    public function mealItem(): BelongsTo
    {
        return $this->belongsTo(MealItem::class, 'meal_item_id');
    }
}

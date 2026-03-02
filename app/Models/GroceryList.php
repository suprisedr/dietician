<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroceryList extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'week_id',
        'name',
    ];

    const CATEGORIES = ['pantry', 'produce', 'meat', 'dairy', 'bakery', 'household'];

    const CATEGORY_LABELS = [
        'pantry'     => 'Pantry',
        'produce'    => 'Produce',
        'meat'       => 'Meat',
        'dairy'      => 'Dairy',
        'bakery'     => 'Bakery',
        'household'  => 'Household',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function week(): BelongsTo
    {
        return $this->belongsTo(MealPlannerWeek::class, 'week_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(GroceryListItem::class);
    }
}

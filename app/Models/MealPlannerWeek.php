<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MealPlannerWeek extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'week_start',
        'label',
    ];

    protected $casts = [
        'week_start' => 'date',
    ];

    // ── Days of week labels ───────────────────────────────────────────────────
    const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    // 0 = Mon … 6 = Sun
    const MEAL_SLOTS = ['breakfast', 'snack1', 'lunch', 'snack2', 'dinner', 'snack3'];

    const SLOT_LABELS = [
        'breakfast' => 'Breakfast',
        'snack1'    => 'Snack 1',
        'lunch'     => 'Lunch',
        'snack2'    => 'Snack 2',
        'dinner'    => 'Dinner',
        'snack3'    => 'Snack 3',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(MealPlannerEntry::class, 'week_id');
    }

    public function groceryList(): HasOne
    {
        return $this->hasOne(GroceryList::class, 'week_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Returns a grid: $grid[$dayIndex][$slot] = MealPlannerEntry|null
     */
    /**
     * Returns a grid: $grid[$dayIndex][$slot] = array of MealPlannerEntry (may be empty)
     * Entries within a cell are sorted by sort_order.
     */
    public function getGridAttribute(): array
    {
        $grid = [];
        foreach (range(0, 6) as $day) {
            foreach (self::MEAL_SLOTS as $slot) {
                $grid[$day][$slot] = [];
            }
        }
        foreach ($this->entries->sortBy('sort_order') as $entry) {
            $grid[$entry->day_of_week][$entry->meal_slot][] = $entry;
        }
        return $grid;
    }
}

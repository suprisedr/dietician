<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroceryListItem extends Model
{
    protected $fillable = [
        'grocery_list_id',
        'category',
        'item',
        'checked',
    ];

    protected $casts = [
        'checked' => 'boolean',
    ];

    public function list(): BelongsTo
    {
        return $this->belongsTo(GroceryList::class, 'grocery_list_id');
    }
}

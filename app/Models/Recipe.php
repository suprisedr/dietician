<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    protected $fillable = [
        'user_id',
        'fatsecret_recipe_id',
        'name',
        'description',
        'image_url',
        'source_url',
        'serving_size',
        'calories',
        'fat_g',
        'carbs_g',
        'protein_g',
        'fiber_g',
        'ingredients',
        'directions',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'calories'    => 'decimal:2',
        'fat_g'       => 'decimal:2',
        'carbs_g'     => 'decimal:2',
        'protein_g'   => 'decimal:2',
        'fiber_g'     => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class, 'patient_recipe')
            ->withPivot(['note', 'sent_at'])
            ->withTimestamps();
    }
}

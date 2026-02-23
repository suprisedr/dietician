<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeTemplateItem extends Model
{
    protected $table = 'exchange_template_items';

    protected $fillable = [
        'exchange_template_id',
        'name',
        'nu',
        'cho_g',
        'protein_min_g',
        'protein_max_g',
        'fat_min_g',
        'fat_max_g',
        'kj',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ExchangeTemplate::class, 'exchange_template_id');
    }
}

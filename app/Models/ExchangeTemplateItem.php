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
        'slot_breakfast',
        'slot_snack1',
        'slot_lunch',
        'slot_snack2',
        'slot_supper',
        'slot_snack3',
    ];

    protected $casts = [
        'slot_breakfast' => 'float',
        'slot_snack1'    => 'float',
        'slot_lunch'     => 'float',
        'slot_snack2'    => 'float',
        'slot_supper'    => 'float',
        'slot_snack3'    => 'float',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ExchangeTemplate::class, 'exchange_template_id');
    }
}

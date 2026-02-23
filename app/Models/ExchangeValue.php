<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeValue extends Model
{
    protected $table = 'exchange_values';

    protected $fillable = [
        'name',
        'nu',
        'cho_g',
        'protein_min_g',
        'protein_max_g',
        'fat_min_g',
        'fat_max_g',
        'kj',
    ];

    protected $casts = [
        'nu' => 'integer',
        'cho_g' => 'integer',
        'protein_min_g' => 'integer',
        'protein_max_g' => 'integer',
        'fat_min_g' => 'integer',
        'fat_max_g' => 'integer',
        'kj' => 'integer',
    ];
}

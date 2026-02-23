<?php

namespace Database\Seeders;

use App\Models\ExchangeValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExchangeValuesTableSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $rows = [
            ['name' => 'Milk, full cream',     'nu' => 1, 'cho_g' => 12, 'protein_min_g' => 8, 'protein_max_g' => 8, 'fat_min_g' => 8, 'fat_max_g' => 8, 'kj' => 670],
            ['name' => 'Milk, low fat',        'nu' => 1, 'cho_g' => 12, 'protein_min_g' => 8, 'protein_max_g' => 8, 'fat_min_g' => 5, 'fat_max_g' => 5, 'kj' => 500],
            ['name' => 'Milk, fat free',       'nu' => 1, 'cho_g' => 12, 'protein_min_g' => 8, 'protein_max_g' => 8, 'fat_min_g' => 0, 'fat_max_g' => 3, 'kj' => 420],
            ['name' => 'Fruit',                'nu' => 1, 'cho_g' => 15, 'protein_min_g' => null, 'protein_max_g' => null, 'fat_min_g' => null, 'fat_max_g' => null, 'kj' => 250],
            ['name' => 'Veg, free veg',       'nu' => 1, 'cho_g' => 5,  'protein_min_g' => 2, 'protein_max_g' => 2, 'fat_min_g' => null, 'fat_max_g' => null, 'kj' => 105],
            ['name' => 'Starch',               'nu' => 1, 'cho_g' => 15, 'protein_min_g' => 0, 'protein_max_g' => 3, 'fat_min_g' => 0, 'fat_max_g' => 1, 'kj' => 335],
            ['name' => 'Sugar/sweets',         'nu' => 1, 'cho_g' => 5,  'protein_min_g' => null, 'protein_max_g' => null, 'fat_min_g' => null, 'fat_max_g' => null, 'kj' => 84],
            ['name' => 'Meat, lean fat',      'nu' => 1, 'cho_g' => null, 'protein_min_g' => 7, 'protein_max_g' => 7, 'fat_min_g' => 0, 'fat_max_g' => 3, 'kj' => 190],
            ['name' => 'Meat, medium fat',    'nu' => 1, 'cho_g' => null, 'protein_min_g' => 7, 'protein_max_g' => 7, 'fat_min_g' => 4, 'fat_max_g' => 7, 'kj' => 315],
            ['name' => 'Meat, high fat',      'nu' => 1, 'cho_g' => null, 'protein_min_g' => 7, 'protein_max_g' => 7, 'fat_min_g' => 8, 'fat_max_g' => 8, 'kj' => 420],
            ['name' => 'Plant-based protein',  'nu' => 1, 'cho_g' => 15, 'protein_min_g' => 7, 'protein_max_g' => 7, 'fat_min_g' => 0, 'fat_max_g' => 1, 'kj' => 380],
            ['name' => 'Fat',                  'nu' => 1, 'cho_g' => null, 'protein_min_g' => null, 'protein_max_g' => null, 'fat_min_g' => 5, 'fat_max_g' => 5, 'kj' => 190],
            ['name' => 'Alcohol',              'nu' => 1, 'cho_g' => 15, 'protein_min_g' => 0, 'protein_max_g' => 3, 'fat_min_g' => 0, 'fat_max_g' => 1, 'kj' => 420],
        ];

        foreach ($rows as $r) {
            ExchangeValue::updateOrCreate(['name' => $r['name']], $r);
        }
    }
}

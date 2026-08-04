<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('meal_items')->where('is_system', true)->delete();

        $now = now();

        $items = [

            // ══════════════════════════════════════════════════════════════
            //  STARCH
            // ══════════════════════════════════════════════════════════════
            ['category' => 'Starch', 'name' => 'Brown/whole wheat bread',    'serving_size' => '1 slice (35g)',             'cho_g' => 15, 'protein_g' => 3,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'White bread',                'serving_size' => '1 slice (35g)',             'cho_g' => 15, 'protein_g' => 3,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Pap, stiff',                 'serving_size' => '1/2 cup cooked (100g)',     'cho_g' => 25, 'protein_g' => 2,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Soft maize porridge',        'serving_size' => '1 cup cooked (250ml)',      'cho_g' => 38, 'protein_g' => 4,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Oats, cooked',              'serving_size' => '1 cup cooked (230ml)',      'cho_g' => 27, 'protein_g' => 5,  'fat_g' => 3,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Weet-Bix',                  'serving_size' => '2 biscuits (30g)',          'cho_g' => 22, 'protein_g' => 4,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Cornflakes',                'serving_size' => '1 cup (30g)',               'cho_g' => 25, 'protein_g' => 2,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Rice, cooked',              'serving_size' => '1/2 cup (80g)',             'cho_g' => 23, 'protein_g' => 2,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Brown rice, cooked',        'serving_size' => '1/2 cup (90g)',             'cho_g' => 23, 'protein_g' => 2,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Samp, cooked',              'serving_size' => '1/2 cup (100g)',            'cho_g' => 27, 'protein_g' => 3,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Pasta, cooked',             'serving_size' => '1/2 cup (80g)',             'cho_g' => 22, 'protein_g' => 4,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Sweet potato',              'serving_size' => '1/2 cup (100g)',            'cho_g' => 20, 'protein_g' => 2,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Potato',                    'serving_size' => '1 small (100g)',            'cho_g' => 19, 'protein_g' => 2,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Butternut',                 'serving_size' => '1 cup (160g)',              'cho_g' => 18, 'protein_g' => 2,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Pumpkin',                   'serving_size' => '1 cup (160g)',              'cho_g' => 12, 'protein_g' => 2,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Cream crackers',            'serving_size' => '2 crackers (15g)',          'cho_g' => 10, 'protein_g' => 1,  'fat_g' => 3,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Mageu',                     'serving_size' => '1 cup (250ml)',             'cho_g' => 36, 'protein_g' => 3,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Starch', 'name' => 'Sorghum/Ting porridge',     'serving_size' => '1 cup cooked (250ml)',      'cho_g' => 38, 'protein_g' => 5,  'fat_g' => 1,  'fruit_veg_portions' => 0],

            // ══════════════════════════════════════════════════════════════
            //  PROTEIN – ANIMAL
            // ══════════════════════════════════════════════════════════════
            ['category' => 'Protein - Animal', 'name' => 'Chicken, skinless',           'serving_size' => '30g cooked',              'cho_g' => 0,  'protein_g' => 9,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Chicken with skin',           'serving_size' => '30g cooked',              'cho_g' => 0,  'protein_g' => 8,  'fat_g' => 4,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Lean beef',                   'serving_size' => '30g cooked',              'cho_g' => 0,  'protein_g' => 8,  'fat_g' => 4,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Beef stew meat',              'serving_size' => '60g cooked',              'cho_g' => 0,  'protein_g' => 14, 'fat_g' => 10, 'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Mince, lean',                 'serving_size' => '60g cooked',              'cho_g' => 0,  'protein_g' => 14, 'fat_g' => 8,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Fish, grilled',               'serving_size' => '40g cooked',              'cho_g' => 0,  'protein_g' => 9,  'fat_g' => 2,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Pilchards in tomato sauce',   'serving_size' => '1/2 small tin (80g)',     'cho_g' => 3,  'protein_g' => 14, 'fat_g' => 8,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Tuna in water',               'serving_size' => '1/2 tin (60g)',           'cho_g' => 0,  'protein_g' => 15, 'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Egg',                          'serving_size' => '1 large (50g)',           'cho_g' => 0,  'protein_g' => 6,  'fat_g' => 5,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Boiled egg whites',           'serving_size' => '2 whites (60g)',          'cho_g' => 0,  'protein_g' => 7,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Vienna/processed meat',       'serving_size' => '1 small (40g)',           'cho_g' => 2,  'protein_g' => 5,  'fat_g' => 9,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Animal', 'name' => 'Polony',                      'serving_size' => '2 thin slices (30g)',     'cho_g' => 2,  'protein_g' => 4,  'fat_g' => 7,  'fruit_veg_portions' => 0],

            // ══════════════════════════════════════════════════════════════
            //  PROTEIN – PLANT
            // ══════════════════════════════════════════════════════════════
            ['category' => 'Protein - Plant', 'name' => 'Sugar beans, cooked',  'serving_size' => '1/2 cup (90g)',   'cho_g' => 20, 'protein_g' => 7,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Plant', 'name' => 'Baked beans',          'serving_size' => '1/2 cup (130g)',  'cho_g' => 20, 'protein_g' => 6,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Plant', 'name' => 'Lentils, cooked',      'serving_size' => '1/2 cup (100g)', 'cho_g' => 20, 'protein_g' => 9,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Plant', 'name' => 'Chickpeas, cooked',    'serving_size' => '1/2 cup (90g)',  'cho_g' => 22, 'protein_g' => 7,  'fat_g' => 2,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Plant', 'name' => 'Split peas, cooked',   'serving_size' => '1/2 cup (100g)', 'cho_g' => 20, 'protein_g' => 8,  'fat_g' => 1,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Plant', 'name' => 'Soya mince, cooked',   'serving_size' => '1/2 cup (80g)',  'cho_g' => 10, 'protein_g' => 15, 'fat_g' => 3,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Plant', 'name' => 'Tofu',                 'serving_size' => '100g',           'cho_g' => 2,  'protein_g' => 8,  'fat_g' => 5,  'fruit_veg_portions' => 0],
            ['category' => 'Protein - Plant', 'name' => 'Peanut butter',        'serving_size' => '1 tbsp (15g)',   'cho_g' => 3,  'protein_g' => 4,  'fat_g' => 8,  'fruit_veg_portions' => 0],

            // ══════════════════════════════════════════════════════════════
            //  FRUIT
            // ══════════════════════════════════════════════════════════════
            ['category' => 'Fruit', 'name' => 'Apple',             'serving_size' => '1 medium (150g)',      'cho_g' => 20, 'protein_g' => 0, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Banana',            'serving_size' => '1 small (100g)',       'cho_g' => 23, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Orange',            'serving_size' => '1 medium (130g)',      'cho_g' => 15, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Pear',              'serving_size' => '1 medium (150g)',      'cho_g' => 22, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Naartjie',          'serving_size' => '2 small (140g)',       'cho_g' => 17, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Grapes',            'serving_size' => '1 cup (150g)',         'cho_g' => 26, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Watermelon',        'serving_size' => '1 cup cubes (150g)',   'cho_g' => 11, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Pawpaw',            'serving_size' => '1 cup cubes (140g)',   'cho_g' => 15, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Mango',             'serving_size' => '1/2 medium (100g)',    'cho_g' => 16, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Pineapple',         'serving_size' => '1 cup (160g)',         'cho_g' => 20, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Peach',             'serving_size' => '1 medium (150g)',      'cho_g' => 15, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Fruit', 'name' => 'Dried fruit',       'serving_size' => '2 tbsp (25g)',         'cho_g' => 19, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],

            // ══════════════════════════════════════════════════════════════
            //  VEGETABLE
            // ══════════════════════════════════════════════════════════════
            ['category' => 'Vegetable', 'name' => 'Spinach/morogo',      'serving_size' => '1 cup cooked (100g)',  'cho_g' => 5,  'protein_g' => 3, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Cabbage',             'serving_size' => '1 cup cooked (100g)',  'cho_g' => 6,  'protein_g' => 2, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Green beans',         'serving_size' => '1 cup cooked (100g)',  'cho_g' => 7,  'protein_g' => 2, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Mixed vegetables',    'serving_size' => '1 cup cooked (150g)', 'cho_g' => 15, 'protein_g' => 3, 'fat_g' => 1, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Carrots',             'serving_size' => '1 cup cooked (130g)', 'cho_g' => 12, 'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Tomato',              'serving_size' => '1 medium (120g)',      'cho_g' => 5,  'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Onion',               'serving_size' => '1/2 cup cooked (80g)','cho_g' => 8,  'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Chakalaka',           'serving_size' => '1/2 cup (100g)',       'cho_g' => 12, 'protein_g' => 3, 'fat_g' => 4, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Beetroot',            'serving_size' => '1/2 cup (85g)',        'cho_g' => 8,  'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Broccoli',            'serving_size' => '1 cup cooked (150g)', 'cho_g' => 10, 'protein_g' => 4, 'fat_g' => 1, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Cauliflower',         'serving_size' => '1 cup cooked (125g)', 'cho_g' => 7,  'protein_g' => 3, 'fat_g' => 0, 'fruit_veg_portions' => 1],
            ['category' => 'Vegetable', 'name' => 'Lettuce/salad',       'serving_size' => '2 cups (100g)',        'cho_g' => 5,  'protein_g' => 1, 'fat_g' => 0, 'fruit_veg_portions' => 1],

            // ══════════════════════════════════════════════════════════════
            //  DAIRY
            // ══════════════════════════════════════════════════════════════
            ['category' => 'Dairy', 'name' => 'Low-fat milk',               'serving_size' => '1 cup (250ml)',     'cho_g' => 12, 'protein_g' => 8,  'fat_g' => 3,  'fruit_veg_portions' => 0],
            ['category' => 'Dairy', 'name' => 'Full cream milk',            'serving_size' => '1 cup (250ml)',     'cho_g' => 12, 'protein_g' => 8,  'fat_g' => 8,  'fruit_veg_portions' => 0],
            ['category' => 'Dairy', 'name' => 'Skim milk',                  'serving_size' => '1 cup (250ml)',     'cho_g' => 12, 'protein_g' => 8,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Dairy', 'name' => 'Amasi, plain',               'serving_size' => '1 cup (250ml)',     'cho_g' => 12, 'protein_g' => 8,  'fat_g' => 6,  'fruit_veg_portions' => 0],
            ['category' => 'Dairy', 'name' => 'Low-fat yoghurt, plain',     'serving_size' => '175g tub',          'cho_g' => 15, 'protein_g' => 8,  'fat_g' => 2,  'fruit_veg_portions' => 0],
            ['category' => 'Dairy', 'name' => 'Full cream yoghurt, plain',  'serving_size' => '175g tub',          'cho_g' => 15, 'protein_g' => 7,  'fat_g' => 6,  'fruit_veg_portions' => 0],
            ['category' => 'Dairy', 'name' => 'Cheese, low-fat',            'serving_size' => '30g',               'cho_g' => 1,  'protein_g' => 8,  'fat_g' => 4,  'fruit_veg_portions' => 0],
            ['category' => 'Dairy', 'name' => 'Cheese, cheddar',            'serving_size' => '30g',               'cho_g' => 1,  'protein_g' => 7,  'fat_g' => 10, 'fruit_veg_portions' => 0],
            ['category' => 'Dairy', 'name' => 'Custard',                    'serving_size' => '1/2 cup (125ml)',   'cho_g' => 22, 'protein_g' => 4,  'fat_g' => 3,  'fruit_veg_portions' => 0],

            // ══════════════════════════════════════════════════════════════
            //  FAT
            // ══════════════════════════════════════════════════════════════
            ['category' => 'Fat', 'name' => 'Cooking oil',      'serving_size' => '1 tsp (5ml)',        'cho_g' => 0, 'protein_g' => 0, 'fat_g' => 5,  'fruit_veg_portions' => 0],
            ['category' => 'Fat', 'name' => 'Margarine',        'serving_size' => '1 tsp (5g)',         'cho_g' => 0, 'protein_g' => 0, 'fat_g' => 4,  'fruit_veg_portions' => 0],
            ['category' => 'Fat', 'name' => 'Butter',           'serving_size' => '1 tsp (5g)',         'cho_g' => 0, 'protein_g' => 0, 'fat_g' => 4,  'fruit_veg_portions' => 0],
            ['category' => 'Fat', 'name' => 'Mayonnaise',       'serving_size' => '1 tbsp (15ml)',      'cho_g' => 0, 'protein_g' => 0, 'fat_g' => 11, 'fruit_veg_portions' => 0],
            ['category' => 'Fat', 'name' => 'Avocado',          'serving_size' => '1/4 medium (50g)',   'cho_g' => 4, 'protein_g' => 1, 'fat_g' => 7,  'fruit_veg_portions' => 0],
            ['category' => 'Fat', 'name' => 'Peanuts',          'serving_size' => '1 tbsp (15g)',       'cho_g' => 3, 'protein_g' => 4, 'fat_g' => 7,  'fruit_veg_portions' => 0],
            ['category' => 'Fat', 'name' => 'Almonds',          'serving_size' => '6 nuts (10g)',       'cho_g' => 2, 'protein_g' => 2, 'fat_g' => 5,  'fruit_veg_portions' => 0],
            ['category' => 'Fat', 'name' => 'Sunflower seeds',  'serving_size' => '1 tbsp (10g)',       'cho_g' => 2, 'protein_g' => 2, 'fat_g' => 5,  'fruit_veg_portions' => 0],

            // ══════════════════════════════════════════════════════════════
            //  OTHER / LIMIT
            // ══════════════════════════════════════════════════════════════
            ['category' => 'Other/Limit', 'name' => 'Sugar',          'serving_size' => '1 tsp (5g)',            'cho_g' => 5,  'protein_g' => 0,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Other/Limit', 'name' => 'Honey/jam',      'serving_size' => '1 tsp (7g)',            'cho_g' => 5,  'protein_g' => 0,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Other/Limit', 'name' => 'Soft drink',     'serving_size' => '1 cup (250ml)',         'cho_g' => 25, 'protein_g' => 0,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Other/Limit', 'name' => 'Fruit juice',    'serving_size' => '1/2 cup (125ml)',       'cho_g' => 13, 'protein_g' => 0,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Other/Limit', 'name' => 'Sweets',         'serving_size' => 'Small packet (25g)',    'cho_g' => 24, 'protein_g' => 0,  'fat_g' => 0,  'fruit_veg_portions' => 0],
            ['category' => 'Other/Limit', 'name' => 'Vetkoek',        'serving_size' => '1 medium (80g)',        'cho_g' => 35, 'protein_g' => 5,  'fat_g' => 10, 'fruit_veg_portions' => 0],
            ['category' => 'Other/Limit', 'name' => 'Boerewors',      'serving_size' => '60g cooked',            'cho_g' => 2,  'protein_g' => 10, 'fat_g' => 16, 'fruit_veg_portions' => 0],
            ['category' => 'Other/Limit', 'name' => 'Atchar',         'serving_size' => '1 tbsp (15g)',          'cho_g' => 2,  'protein_g' => 0,  'fat_g' => 3,  'fruit_veg_portions' => 0],

        ];

        $rows = [];
        foreach ($items as $item) {
            $c = $item['cho_g'];
            $p = $item['protein_g'];
            $f = $item['fat_g'];
            $rows[] = [
                'category'            => $item['category'],
                'name'                => $item['name'],
                'serving_size'        => $item['serving_size'],
                'cho_g'               => $c,
                'protein_g'           => $p,
                'fat_g'               => $f,
                'energy_kj'           => round(($c * 17) + ($p * 17) + ($f * 37), 1),
                'energy_kcal'         => round(($c * 4)  + ($p * 4)  + ($f * 9),  1),
                'fruit_veg_portions'  => $item['fruit_veg_portions'],
                'is_system'           => true,
                'created_by'          => null,
                'created_at'          => $now,
                'updated_at'          => $now,
            ];
        }

        DB::table('meal_items')->insert($rows);
    }
}

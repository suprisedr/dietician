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

            // ── FRUIT & VEGETABLES ─────────────────────────────────────────
            ['category' => 'Fruit & Vegetables', 'name' => 'Root vegetables',            'serving_size' => 'Three heaped tablespoons (80g)',                  'cho_g' => 7,  'protein_g' => 1,   'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Corn on the cob',             'serving_size' => 'One whole',                                       'cho_g' => 16, 'protein_g' => 3,   'fat_g' => 1,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Salad',                       'serving_size' => 'One dessert bowl',                                'cho_g' => 3,  'protein_g' => 1,   'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Whole fresh fruit',           'serving_size' => 'One fruit',                                       'cho_g' => 15, 'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Medium fruits',               'serving_size' => 'Two fruits (80g)',                                'cho_g' => 10, 'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Small fruits',                'serving_size' => 'One handful (80g)',                               'cho_g' => 10, 'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Large fruits',                'serving_size' => 'One slice, about 5cm thick (80g)',                'cho_g' => 10, 'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Grapefruit',                  'serving_size' => 'Half (80g)',                                      'cho_g' => 8,  'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Tinned fruit in juice',       'serving_size' => 'Three heaped tablespoons',                        'cho_g' => 15, 'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 2],
            ['category' => 'Fruit & Vegetables', 'name' => 'Stewed fruit',                'serving_size' => 'Three heaped tablespoons',                        'cho_g' => 12, 'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Dried fruit',                 'serving_size' => 'One heaped tablespoon (30g)',                     'cho_g' => 20, 'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Fruit & Vegetables', 'name' => 'Fruit juice',                 'serving_size' => 'One small glass or carton (150ml); max one/day',  'cho_g' => 15, 'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 1],

            // ── STARCHY FOODS ──────────────────────────────────────────────
            ['category' => 'Starchy Foods', 'name' => 'Muesli (not crunchy)',             'serving_size' => 'Two tablespoons (20g)',                           'cho_g' => 13, 'protein_g' => 2,   'fat_g' => 1.5, 'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Muesli (crunchy/granola)',         'serving_size' => 'One tablespoon (20g)',                            'cho_g' => 14, 'protein_g' => 2,   'fat_g' => 3,   'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Weetabix',                         'serving_size' => 'One biscuit',                                     'cho_g' => 13, 'protein_g' => 2,   'fat_g' => 0.5, 'fruit_veg_portions' => 2],
            ['category' => 'Starchy Foods', 'name' => 'Shredded wheat',                   'serving_size' => 'One biscuit',                                     'cho_g' => 13, 'protein_g' => 2,   'fat_g' => 0.5, 'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Bread or toast',                   'serving_size' => 'One slice, medium thickness',                     'cho_g' => 15, 'protein_g' => 2.5, 'fat_g' => 1,   'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Bread bun or roll',                'serving_size' => 'Half a large bun/roll (30g)',                     'cho_g' => 15, 'protein_g' => 2.5, 'fat_g' => 1,   'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Pitta bread',                      'serving_size' => 'Half a pitta or one mini',                        'cho_g' => 15, 'protein_g' => 2.5, 'fat_g' => 0.5, 'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Chapatti',                         'serving_size' => 'One small',                                       'cho_g' => 15, 'protein_g' => 2,   'fat_g' => 2,   'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Crumpet / pikelet',                'serving_size' => 'One',                                             'cho_g' => 14, 'protein_g' => 2,   'fat_g' => 0.5, 'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'English muffin',                   'serving_size' => 'Half a whole',                                    'cho_g' => 15, 'protein_g' => 2.5, 'fat_g' => 1,   'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Malt loaf',                        'serving_size' => 'One small slice (35g)',                           'cho_g' => 20, 'protein_g' => 2,   'fat_g' => 1,   'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Crackers',                         'serving_size' => 'Three',                                           'cho_g' => 14, 'protein_g' => 1.5, 'fat_g' => 2,   'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Crispbreads',                      'serving_size' => 'Four',                                            'cho_g' => 14, 'protein_g' => 1.5, 'fat_g' => 1,   'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Oats',                             'serving_size' => 'Three tablespoons (20g uncooked / 40g cooked)',   'cho_g' => 14, 'protein_g' => 2,   'fat_g' => 1.5, 'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Yam (boiled)',                     'serving_size' => 'Two egg-sized pieces (60g cooked)',                'cho_g' => 14, 'protein_g' => 1,   'fat_g' => 0,   'fruit_veg_portions' => 1],
            ['category' => 'Starchy Foods', 'name' => 'Rice (plain boiled)',              'serving_size' => 'Two heaped tablespoons (80g)',                    'cho_g' => 28, 'protein_g' => 2,   'fat_g' => 0,   'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Couscous (plain cooked)',          'serving_size' => 'Two tablespoons (40g)',                            'cho_g' => 11, 'protein_g' => 1.5, 'fat_g' => 0,   'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Quinoa (plain cooked)',            'serving_size' => 'Two heaped tablespoons (80g)',                    'cho_g' => 18, 'protein_g' => 3,   'fat_g' => 1.5, 'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Breakfast cereal (flakes etc)',   'serving_size' => 'Three tablespoons (20g)',                          'cho_g' => 15, 'protein_g' => 1.5, 'fat_g' => 0.5, 'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Wrap',                            'serving_size' => 'Half',                                             'cho_g' => 18, 'protein_g' => 2.5, 'fat_g' => 2,   'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Egg noodles',                     'serving_size' => 'Half dry serving (25g) / 3 tbsp cooked (80g)',    'cho_g' => 18, 'protein_g' => 3,   'fat_g' => 0.5, 'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Potatoes (boiled)',               'serving_size' => 'Two egg-sized',                                    'cho_g' => 20, 'protein_g' => 2,   'fat_g' => 0,   'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Plantain (steamed)',              'serving_size' => 'One medium-sized',                                 'cho_g' => 28, 'protein_g' => 1,   'fat_g' => 0,   'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Bagel (plain/cinnamon & raisin)','serving_size' => 'Half',                                              'cho_g' => 25, 'protein_g' => 3,   'fat_g' => 0.5, 'fruit_veg_portions' => 0],
            ['category' => 'Starchy Foods', 'name' => 'Pasta (plain boiled)',            'serving_size' => 'Three heaped tablespoons (80g)',                   'cho_g' => 25, 'protein_g' => 3,   'fat_g' => 0.5, 'fruit_veg_portions' => 0],

            // ── PROTEIN ────────────────────────────────────────────────────
            ['category' => 'Protein', 'name' => 'Cooked lean meat (skinless)',    'serving_size' => 'Pack-of-cards sized piece (60–90g)',         'cho_g' => 0,  'protein_g' => 20,  'fat_g' => 4,   'fruit_veg_portions' => 0],
            ['category' => 'Protein', 'name' => 'White fish',                     'serving_size' => 'One medium fillet (150g raw)',                'cho_g' => 0,  'protein_g' => 25,  'fat_g' => 1,   'fruit_veg_portions' => 1],
            ['category' => 'Protein', 'name' => 'Oily fish',                      'serving_size' => 'One medium fillet (140g raw)',                'cho_g' => 0,  'protein_g' => 24,  'fat_g' => 8,   'fruit_veg_portions' => 1],
            ['category' => 'Protein', 'name' => 'Fish fingers',                   'serving_size' => 'Three',                                       'cho_g' => 12, 'protein_g' => 10,  'fat_g' => 6,   'fruit_veg_portions' => 1],
            ['category' => 'Protein', 'name' => 'Baked beans in tomato sauce',    'serving_size' => 'One small tin (200g)',                        'cho_g' => 24, 'protein_g' => 10,  'fat_g' => 0.5, 'fruit_veg_portions' => 0],
            ['category' => 'Protein', 'name' => 'Lentils',                        'serving_size' => 'Five tablespoons cooked',                     'cho_g' => 20, 'protein_g' => 9,   'fat_g' => 0.5, 'fruit_veg_portions' => 0],
            ['category' => 'Protein', 'name' => 'Beans (kidney/butter/chickpea)', 'serving_size' => 'Five tablespoons cooked (140g)',              'cho_g' => 22, 'protein_g' => 9,   'fat_g' => 0.5, 'fruit_veg_portions' => 0],
            ['category' => 'Protein', 'name' => 'Peanut butter (unsalted)',       'serving_size' => 'Two level tablespoons',                       'cho_g' => 6,  'protein_g' => 7,   'fat_g' => 16,  'fruit_veg_portions' => 0],
            ['category' => 'Protein', 'name' => 'Quorn, tofu or soya',            'serving_size' => 'Two sausages or 120g uncooked',               'cho_g' => 3,  'protein_g' => 14,  'fat_g' => 5,   'fruit_veg_portions' => 0],
            ['category' => 'Protein', 'name' => 'Eggs',                           'serving_size' => 'Two',                                         'cho_g' => 0,  'protein_g' => 12,  'fat_g' => 10,  'fruit_veg_portions' => 0],
            ['category' => 'Protein', 'name' => 'Nuts (unsalted)',                'serving_size' => 'Two level tablespoons',                       'cho_g' => 3,  'protein_g' => 4,   'fat_g' => 12,  'fruit_veg_portions' => 0],

            // ── MILK & DAIRY ───────────────────────────────────────────────
            ['category' => 'Milk & Dairy', 'name' => 'Milk (semi-skimmed or skimmed)', 'serving_size' => 'One glass (200ml)',           'cho_g' => 10, 'protein_g' => 7,   'fat_g' => 2,   'fruit_veg_portions' => 0],
            ['category' => 'Milk & Dairy', 'name' => 'Yoghurt (low-fat, low-sugar)',   'serving_size' => 'Large pot (200g)',             'cho_g' => 14, 'protein_g' => 10,  'fat_g' => 1,   'fruit_veg_portions' => 0],
            ['category' => 'Milk & Dairy', 'name' => 'Cheese (lower-fat e.g. Edam)',   'serving_size' => 'Matchbox-sized piece (30g)',  'cho_g' => 0,  'protein_g' => 7,   'fat_g' => 7,   'fruit_veg_portions' => 0],
            ['category' => 'Milk & Dairy', 'name' => 'Cream cheese (reduced-fat)',     'serving_size' => 'Matchbox-sized piece (80g)',  'cho_g' => 2,  'protein_g' => 5,   'fat_g' => 9,   'fruit_veg_portions' => 0],
            ['category' => 'Milk & Dairy', 'name' => 'Fromage frais',                  'serving_size' => 'Small pot (150g)',             'cho_g' => 8,  'protein_g' => 8,   'fat_g' => 2,   'fruit_veg_portions' => 1],
            ['category' => 'Milk & Dairy', 'name' => 'Low-fat cottage cheese',         'serving_size' => 'Large pot (200g)',             'cho_g' => 6,  'protein_g' => 24,  'fat_g' => 2,   'fruit_veg_portions' => 1],

            // ── SPREADING FAT, OIL & SAUCE ─────────────────────────────────
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Low-fat spread',                  'serving_size' => 'Two teaspoons',    'cho_g' => 0,  'protein_g' => 0,   'fat_g' => 4,   'fruit_veg_portions' => 0],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Oil (unsaturated)',               'serving_size' => 'One teaspoon',     'cho_g' => 0,  'protein_g' => 0,   'fat_g' => 4.5, 'fruit_veg_portions' => 0],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Butter / margarine / ghee',      'serving_size' => 'One teaspoon',     'cho_g' => 0,  'protein_g' => 0,   'fat_g' => 4.5, 'fruit_veg_portions' => 2],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Mayonnaise',                      'serving_size' => 'One teaspoon',     'cho_g' => 0,  'protein_g' => 0,   'fat_g' => 4,   'fruit_veg_portions' => 2],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Blue cheese dressing',            'serving_size' => 'One teaspoon',     'cho_g' => 0.5,'protein_g' => 0.5, 'fat_g' => 4,   'fruit_veg_portions' => 1],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Salad cream',                     'serving_size' => 'One tablespoon',   'cho_g' => 2,  'protein_g' => 0,   'fat_g' => 3,   'fruit_veg_portions' => 1],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Gravy / white sauce (fat base)',  'serving_size' => 'Two tablespoons',  'cho_g' => 4,  'protein_g' => 0.5, 'fat_g' => 3,   'fruit_veg_portions' => 0],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Gravy / white sauce (cornflour)', 'serving_size' => 'Four tablespoons', 'cho_g' => 5,  'protein_g' => 0.5, 'fat_g' => 0,   'fruit_veg_portions' => 0],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Cream (single)',                  'serving_size' => 'Two tablespoons',  'cho_g' => 1,  'protein_g' => 0.5, 'fat_g' => 6,   'fruit_veg_portions' => 2],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Low-fat crème fraîche',           'serving_size' => 'Six tablespoons',  'cho_g' => 4,  'protein_g' => 2,   'fat_g' => 5,   'fruit_veg_portions' => 1],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Low-calorie mayonnaise',          'serving_size' => 'Two tablespoons',  'cho_g' => 2,  'protein_g' => 0,   'fat_g' => 2,   'fruit_veg_portions' => 2],
            ['category' => 'Spreading Fat, Oil & Sauce', 'name' => 'Cream (double) / crème fraîche',  'serving_size' => 'Two tablespoons', 'cho_g' => 1,  'protein_g' => 0.5, 'fat_g' => 12,  'fruit_veg_portions' => 0],
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

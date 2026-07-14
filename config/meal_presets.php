<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Meal Plan Preset Templates
    |--------------------------------------------------------------------------
    |
    | Each preset maps meal slots to food item names (matched against the
    | meal_items table). The same pattern is applied to every day of the week.
    | Slots: breakfast, snack1, lunch, snack2, dinner, snack3
    |
    */

    'weight_loss_1500' => [
        'name'  => 'Weight Loss (1500 kcal)',
        'kcal'  => 1500,
        'slots' => [
            'breakfast' => ['Brown/whole wheat bread', 'Brown/whole wheat bread', 'Egg', 'Egg', 'Tomato'],
            'snack1'    => ['Apple'],
            'lunch'     => ['Chicken, skinless', 'Chicken, skinless', 'Chicken, skinless', 'Mixed vegetables', 'Brown rice, cooked'],
            'snack2'    => ['Low-fat yoghurt, plain'],
            'dinner'    => ['Fish, grilled', 'Mixed vegetables', 'Sweet potato'],
        ],
    ],

    'weight_loss_1800' => [
        'name'  => 'Weight Loss (1800 kcal)',
        'kcal'  => 1800,
        'slots' => [
            'breakfast' => ['Oats, cooked', 'Low-fat milk', 'Banana'],
            'snack1'    => ['Apple'],
            'lunch'     => ['Chicken, skinless', 'Chicken, skinless', 'Rice, cooked', 'Mixed vegetables'],
            'snack2'    => ['Low-fat yoghurt, plain'],
            'dinner'    => ['Fish, grilled', 'Sweet potato', 'Lettuce/salad'],
        ],
    ],

    'diabetes_1800' => [
        'name'  => 'Type 2 Diabetes (1800 kcal)',
        'kcal'  => 1800,
        'slots' => [
            'breakfast' => ['Oats, cooked', 'Low-fat milk', 'Apple'],
            'snack1'    => ['Pear'],
            'lunch'     => ['Chicken, skinless', 'Chicken, skinless', 'Brown rice, cooked', 'Spinach/morogo'],
            'snack2'    => ['Low-fat yoghurt, plain'],
            'dinner'    => ['Fish, grilled', 'Sweet potato', 'Mixed vegetables'],
        ],
    ],

    'diabetes_2000' => [
        'name'  => 'Type 2 Diabetes (2000 kcal)',
        'kcal'  => 2000,
        'slots' => [
            'breakfast' => ['Weet-Bix', 'Low-fat milk', 'Banana'],
            'snack1'    => ['Apple'],
            'lunch'     => ['Beef stew meat', 'Samp, cooked', 'Pumpkin'],
            'snack2'    => ['Low-fat yoghurt, plain'],
            'dinner'    => ['Chicken, skinless', 'Chicken, skinless', 'Chicken, skinless', 'Rice, cooked', 'Green beans'],
        ],
    ],

    'hypertension_dash' => [
        'name'  => 'Hypertension (DASH)',
        'kcal'  => null,
        'slots' => [
            'breakfast' => ['Oats, cooked', 'Low-fat milk', 'Banana'],
            'snack1'    => ['Orange'],
            'lunch'     => ['Fish, grilled', 'Brown rice, cooked', 'Spinach/morogo'],
            'snack2'    => ['Almonds'],
            'dinner'    => ['Chicken, skinless', 'Chicken, skinless', 'Sweet potato', 'Lettuce/salad'],
        ],
    ],

    'pregnancy_2200' => [
        'name'  => 'Pregnancy (2200 kcal)',
        'kcal'  => 2200,
        'slots' => [
            'breakfast' => ['Oats, cooked', 'Low-fat milk', 'Banana'],
            'snack1'    => ['Low-fat yoghurt, plain'],
            'lunch'     => ['Chicken, skinless', 'Chicken, skinless', 'Rice, cooked', 'Mixed vegetables'],
            'snack2'    => ['Apple', 'Almonds'],
            'dinner'    => ['Fish, grilled', 'Sweet potato', 'Mixed vegetables'],
            'snack3'    => ['Low-fat milk'],
        ],
    ],

    'hehp' => [
        'name'  => 'HEHP',
        'kcal'  => null,
        'slots' => [
            'breakfast' => ['Peanut butter fortified porridge', 'Peanut butter', 'Low-fat milk'],
            'snack1'    => ['Oral nutrition supplement powder'],
            'lunch'     => ['Beef stew meat', 'Rice, cooked', 'Mixed vegetables'],
            'snack2'    => ['Low-fat yoghurt, plain'],
            'dinner'    => ['Chicken, skinless', 'Chicken, skinless', 'Potato', 'Mixed vegetables'],
            'snack3'    => ['Full cream milk'],
        ],
    ],

    'hiv_tb_recovery' => [
        'name'  => 'HIV/TB Recovery',
        'kcal'  => null,
        'slots' => [
            'breakfast' => ['Soft maize porridge', 'Low-fat milk', 'Peanut butter'],
            'snack1'    => ['Low-fat yoghurt, plain'],
            'lunch'     => ['Chicken, skinless', 'Chicken, skinless', 'Rice, cooked', 'Mixed vegetables'],
            'snack2'    => ['Apple'],
            'dinner'    => ['Fish, grilled', 'Sweet potato', 'Mixed vegetables'],
            'snack3'    => ['Full cream milk'],
        ],
    ],

    'vegetarian' => [
        'name'  => 'Vegetarian',
        'kcal'  => null,
        'slots' => [
            'breakfast' => ['Oats, cooked', 'Low-fat milk'],
            'snack1'    => ['Apple'],
            'lunch'     => ['Sugar beans, cooked', 'Brown rice, cooked', 'Mixed vegetables'],
            'snack2'    => ['Low-fat yoghurt, plain'],
            'dinner'    => ['Lentils, cooked', 'Sweet potato'],
        ],
    ],

    'budget_sa' => [
        'name'  => 'Budget-Friendly South African',
        'kcal'  => null,
        'slots' => [
            'breakfast' => ['Soft maize porridge', 'Low-fat milk'],
            'snack1'    => ['Apple'],
            'lunch'     => ['Chicken, skinless', 'Chicken, skinless', 'Pap, stiff', 'Spinach/morogo'],
            'snack2'    => ['Amasi, plain'],
            'dinner'    => ['Pilchards in tomato sauce', 'Pap, stiff', 'Chakalaka'],
        ],
    ],

    'renal_pre_dialysis' => [
        'name'  => 'Renal (Pre-Dialysis)',
        'kcal'  => null,
        'slots' => [
            'breakfast' => ['Cornflakes', 'Low-fat milk'],
            'snack1'    => ['Apple'],
            'lunch'     => ['Chicken, skinless', 'Chicken, skinless', 'Rice, cooked', 'Cabbage'],
            'snack2'    => ['Cream crackers'],
            'dinner'    => ['Fish, grilled', 'Rice, cooked', 'Green beans'],
        ],
    ],

    'pediatric_weight_gain' => [
        'name'  => 'Pediatric Weight Gain',
        'kcal'  => null,
        'slots' => [
            'breakfast' => ['Peanut butter fortified porridge'],
            'snack1'    => ['Full cream yoghurt, plain'],
            'lunch'     => ['Mince, lean', 'Rice, cooked'],
            'snack2'    => ['Brown/whole wheat bread', 'Peanut butter'],
            'dinner'    => ['Chicken, skinless', 'Chicken, skinless', 'Potato', 'Mixed vegetables'],
            'snack3'    => ['Full cream milk'],
        ],
    ],

    'cardiac' => [
        'name'  => 'Cardiac Diet',
        'kcal'  => null,
        'slots' => [
            'breakfast' => ['Oats, cooked', 'Low-fat milk'],
            'snack1'    => ['Apple'],
            'lunch'     => ['Fish, grilled', 'Brown rice, cooked', 'Mixed vegetables'],
            'snack2'    => ['Almonds'],
            'dinner'    => ['Chicken, skinless', 'Chicken, skinless', 'Sweet potato', 'Lettuce/salad'],
        ],
    ],

    'traditional_sa' => [
        'name'  => 'Traditional South African Healthy Eating',
        'kcal'  => null,
        'slots' => [
            'breakfast' => ['Soft maize porridge', 'Low-fat milk'],
            'snack1'    => ['Apple'],
            'lunch'     => ['Chicken, skinless', 'Chicken, skinless', 'Pap, stiff', 'Spinach/morogo'],
            'snack2'    => ['Amasi, plain'],
            'dinner'    => ['Fish, grilled', 'Samp, cooked', 'Pumpkin'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 7-Day South African Meal Plan Presets
    |--------------------------------------------------------------------------
    |
    | These presets are starter templates only. Dietitians must individualise
    | portions, exchange counts, energy, protein, fluid, micronutrient needs
    | and clinical restrictions per patient.
    |
    | Each day uses the 'days' key (indexed 0–6, Monday–Sunday) with per-day
    | slot arrays. The planner applies different meals to each day of the week.
    |
    */

    'sa_balanced_adult' => [
        'name'        => 'SA Balanced Adult (1800 kcal)',
        'kcal'        => 1800,
        'category'    => 'General wellness',
        'description' => 'Balanced, affordable South African meals. Portions must be adjusted according to individualised requirements.',
        'days' => [
            0 => [ // Monday
                'breakfast' => ['Soft maize porridge', 'Low-fat milk', 'Egg', 'Orange'],
                'snack1'    => ['Low-fat yoghurt, plain'],
                'lunch'     => ['Chicken, skinless', 'Brown rice, cooked', 'Spinach/morogo', 'Butternut'],
                'snack2'    => ['Apple'],
                'dinner'    => ['Fish, grilled', 'Sweet potato', 'Mixed vegetables'],
            ],
            1 => [ // Tuesday
                'breakfast' => ['Brown/whole wheat bread', 'Peanut butter', 'Banana'],
                'snack1'    => ['Pear'],
                'lunch'     => ['Lean beef', 'Samp, cooked', 'Sugar beans, cooked', 'Cabbage'],
                'snack2'    => ['Peanuts'],
                'dinner'    => ['Chicken, skinless', 'Mixed vegetables', 'Brown rice, cooked'],
            ],
            2 => [ // Wednesday
                'breakfast' => ['Oats, cooked', 'Low-fat milk'],
                'snack1'    => ['Apple'],
                'lunch'     => ['Chicken, skinless', 'Pap, stiff', 'Spinach/morogo'],
                'snack2'    => ['Amasi, plain'],
                'dinner'    => ['Mince, lean', 'Pasta, cooked', 'Lettuce/salad'],
            ],
            3 => [ // Thursday
                'breakfast' => ['Weet-Bix', 'Low-fat milk'],
                'snack1'    => ['Orange'],
                'lunch'     => ['Fish, grilled', 'Sweet potato', 'Green beans'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Chicken, skinless', 'Mixed vegetables', 'Brown rice, cooked'],
            ],
            4 => [ // Friday
                'breakfast' => ['Egg', 'Brown/whole wheat bread'],
                'snack1'    => ['Banana'],
                'lunch'     => ['Chicken, skinless', 'Lettuce/salad'],
                'snack2'    => ['Apple'],
                'dinner'    => ['Lean beef', 'Pumpkin', 'Spinach/morogo'],
            ],
            5 => [ // Saturday
                'breakfast' => ['Oats, cooked', 'Banana'],
                'snack1'    => ['Low-fat yoghurt, plain'],
                'lunch'     => ['Chicken, skinless', 'Brown rice, cooked', 'Broccoli'],
                'snack2'    => ['Apple'],
                'dinner'    => ['Fish, grilled', 'Mixed vegetables'],
            ],
            6 => [ // Sunday
                'breakfast' => ['Soft maize porridge', 'Egg'],
                'snack1'    => ['Orange'],
                'lunch'     => ['Chicken, skinless', 'Pap, stiff', 'Cabbage', 'Carrots'],
                'snack2'    => ['Apple'],
                'dinner'    => ['Mixed vegetables', 'Brown/whole wheat bread'],
            ],
        ],
    ],

    'sa_weight_management' => [
        'name'        => 'SA Weight Management (1500 kcal)',
        'kcal'        => 1500,
        'category'    => 'Weight management',
        'description' => 'Higher vegetable volume, lean proteins, controlled starch portions and limited added fats/sugar.',
        'days' => [
            0 => [ // Monday
                'breakfast' => ['Oats, cooked', 'Low-fat milk', 'Banana'],
                'snack1'    => ['Apple'],
                'lunch'     => ['Chicken, skinless', 'Brown rice, cooked', 'Lettuce/salad'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Fish, grilled', 'Pumpkin', 'Lettuce/salad'],
            ],
            1 => [ // Tuesday
                'breakfast' => ['Egg', 'Egg', 'Brown/whole wheat bread', 'Tomato'],
                'snack1'    => ['Pear'],
                'lunch'     => ['Tuna in water', 'Sugar beans, cooked', 'Sweet potato'],
                'snack2'    => ['Carrots'],
                'dinner'    => ['Chicken, skinless', 'Cabbage', 'Pap, stiff'],
            ],
            2 => [ // Wednesday
                'breakfast' => ['Amasi, plain'],
                'snack1'    => ['Orange'],
                'lunch'     => ['Lean beef', 'Mixed vegetables', 'Brown rice, cooked'],
                'snack2'    => ['Apple'],
                'dinner'    => ['Egg', 'Mixed vegetables', 'Lettuce/salad'],
            ],
            3 => [ // Thursday
                'breakfast' => ['Soft maize porridge', 'Low-fat milk'],
                'snack1'    => ['Banana'],
                'lunch'     => ['Chicken, skinless', 'Lettuce/salad'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Fish, grilled', 'Spinach/morogo', 'Butternut'],
            ],
            4 => [ // Friday
                'breakfast' => ['Weet-Bix', 'Low-fat milk'],
                'snack1'    => ['Apple'],
                'lunch'     => ['Sugar beans, cooked', 'Chicken, skinless', 'Lettuce/salad'],
                'snack2'    => ['Carrots'],
                'dinner'    => ['Mince, lean', 'Lettuce/salad', 'Pasta, cooked'],
            ],
            5 => [ // Saturday
                'breakfast' => ['Egg', 'Brown/whole wheat bread'],
                'snack1'    => ['Apple'],
                'lunch'     => ['Chicken, skinless', 'Mixed vegetables', 'Sweet potato'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Chicken, skinless', 'Mixed vegetables'],
            ],
            6 => [ // Sunday
                'breakfast' => ['Oats, cooked'],
                'snack1'    => ['Orange'],
                'lunch'     => ['Chicken, skinless', 'Cabbage', 'Carrots', 'Pap, stiff'],
                'snack2'    => ['Apple'],
                'dinner'    => ['Fish, grilled', 'Mixed vegetables'],
            ],
        ],
    ],

    'sa_diabetes_low_gi' => [
        'name'        => 'SA Type 2 Diabetes Low GI (1600 kcal)',
        'kcal'        => 1600,
        'category'    => 'Diabetes',
        'description' => 'Carbohydrates are spread across the day. Prioritise low GI/high fibre starches and avoid sugary drinks.',
        'days' => [
            0 => [ // Monday
                'breakfast' => ['Oats, cooked', 'Low-fat milk', 'Egg'],
                'snack1'    => ['Apple', 'Peanut butter'],
                'lunch'     => ['Chicken, skinless', 'Samp, cooked', 'Sugar beans, cooked', 'Spinach/morogo'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Fish, grilled', 'Sweet potato', 'Green beans'],
            ],
            1 => [ // Tuesday
                'breakfast' => ['Amasi, plain'],
                'snack1'    => ['Pear'],
                'lunch'     => ['Lean beef', 'Brown rice, cooked', 'Cabbage'],
                'snack2'    => ['Peanuts'],
                'dinner'    => ['Chicken, skinless', 'Mixed vegetables', 'Lentils, cooked'],
            ],
            2 => [ // Wednesday
                'breakfast' => ['Brown/whole wheat bread', 'Egg', 'Tomato'],
                'snack1'    => ['Orange'],
                'lunch'     => ['Tuna in water', 'Sugar beans, cooked', 'Lettuce/salad'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Chicken, skinless', 'Butternut', 'Spinach/morogo'],
            ],
            3 => [ // Thursday
                'breakfast' => ['Weet-Bix', 'Low-fat milk'],
                'snack1'    => ['Apple'],
                'lunch'     => ['Chicken, skinless', 'Lettuce/salad', 'Avocado'],
                'snack2'    => ['Carrots'],
                'dinner'    => ['Mince, lean', 'Mixed vegetables', 'Pasta, cooked'],
            ],
            4 => [ // Friday
                'breakfast' => ['Soft maize porridge', 'Low-fat milk', 'Egg'],
                'snack1'    => ['Pear'],
                'lunch'     => ['Fish, grilled', 'Brown rice, cooked', 'Mixed vegetables'],
                'snack2'    => ['Amasi, plain'],
                'dinner'    => ['Sugar beans, cooked', 'Cabbage'],
            ],
            5 => [ // Saturday
                'breakfast' => ['Oats, cooked', 'Low-fat yoghurt, plain'],
                'snack1'    => ['Orange'],
                'lunch'     => ['Chicken, skinless', 'Lettuce/salad', 'Sweet potato'],
                'snack2'    => ['Peanuts'],
                'dinner'    => ['Fish, grilled', 'Spinach/morogo', 'Pumpkin'],
            ],
            6 => [ // Sunday
                'breakfast' => ['Egg', 'Brown/whole wheat bread'],
                'snack1'    => ['Apple'],
                'lunch'     => ['Chicken, skinless', 'Pap, stiff', 'Cabbage'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Mixed vegetables', 'Sugar beans, cooked'],
            ],
        ],
    ],

    'sa_hypertension_dash' => [
        'name'        => 'SA Hypertension / DASH-style (1700 kcal)',
        'kcal'        => 1700,
        'category'    => 'Hypertension',
        'description' => 'Limit salt, stock cubes, processed meats and salty snacks. Use herbs, lemon, garlic and spices without salt.',
        'days' => [
            0 => [ // Monday
                'breakfast' => ['Oats, cooked', 'Low-fat milk', 'Banana'],
                'snack1'    => ['Apple'],
                'lunch'     => ['Chicken, skinless', 'Brown rice, cooked', 'Spinach/morogo', 'Lettuce/salad'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Fish, grilled', 'Sweet potato', 'Mixed vegetables'],
            ],
            1 => [ // Tuesday
                'breakfast' => ['Brown/whole wheat bread', 'Egg', 'Tomato'],
                'snack1'    => ['Pear'],
                'lunch'     => ['Sugar beans, cooked', 'Mixed vegetables', 'Pap, stiff'],
                'snack2'    => ['Peanuts'],
                'dinner'    => ['Chicken, skinless', 'Brown rice, cooked', 'Mixed vegetables'],
            ],
            2 => [ // Wednesday
                'breakfast' => ['Amasi, plain', 'Apple'],
                'snack1'    => ['Orange'],
                'lunch'     => ['Tuna in water', 'Sugar beans, cooked', 'Avocado', 'Lettuce/salad'],
                'snack2'    => ['Carrots'],
                'dinner'    => ['Lean beef', 'Cabbage', 'Butternut'],
            ],
            3 => [ // Thursday
                'breakfast' => ['Weet-Bix', 'Low-fat milk'],
                'snack1'    => ['Apple'],
                'lunch'     => ['Chicken, skinless', 'Lettuce/salad'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Fish, grilled', 'Spinach/morogo', 'Sweet potato'],
            ],
            4 => [ // Friday
                'breakfast' => ['Soft maize porridge', 'Low-fat milk'],
                'snack1'    => ['Banana'],
                'lunch'     => ['Lentils, cooked', 'Brown rice, cooked', 'Lettuce/salad'],
                'snack2'    => ['Apple'],
                'dinner'    => ['Chicken, skinless', 'Mixed vegetables'],
            ],
            5 => [ // Saturday
                'breakfast' => ['Oats, cooked'],
                'snack1'    => ['Pear'],
                'lunch'     => ['Fish, grilled', 'Rice, cooked', 'Mixed vegetables'],
                'snack2'    => ['Peanuts'],
                'dinner'    => ['Mixed vegetables', 'Sugar beans, cooked'],
            ],
            6 => [ // Sunday
                'breakfast' => ['Egg', 'Brown/whole wheat bread'],
                'snack1'    => ['Orange'],
                'lunch'     => ['Chicken, skinless', 'Pap, stiff', 'Cabbage', 'Carrots'],
                'snack2'    => ['Low-fat yoghurt, plain'],
                'dinner'    => ['Chicken, skinless', 'Lettuce/salad'],
            ],
        ],
    ],

    'sa_high_protein_high_energy' => [
        'name'        => 'SA High Protein High Energy (2200 kcal)',
        'kcal'        => 2200,
        'category'    => 'Malnutrition / underweight / increased needs',
        'description' => 'Use only when clinically appropriate. Fortify meals with milk powder, peanut butter, oil or margarine as indicated.',
        'days' => [
            0 => [ // Monday
                'breakfast' => ['Soft maize porridge', 'Milk powder fortifier', 'Peanut butter', 'Egg'],
                'snack1'    => ['Amasi, plain', 'Banana'],
                'lunch'     => ['Chicken, skinless', 'Rice, cooked', 'Spinach/morogo', 'Margarine'],
                'snack2'    => ['Low-fat yoghurt, plain', 'Peanuts'],
                'dinner'    => ['Beef stew meat', 'Pap, stiff', 'Butternut'],
            ],
            1 => [ // Tuesday
                'breakfast' => ['Egg', 'Brown/whole wheat bread', 'Avocado'],
                'snack1'    => ['Full cream milk', 'Apple'],
                'lunch'     => ['Chicken, skinless', 'Brown/whole wheat bread', 'Mayonnaise'],
                'snack2'    => ['Brown/whole wheat bread', 'Peanut butter'],
                'dinner'    => ['Fish, grilled', 'Sweet potato', 'Mixed vegetables'],
            ],
            2 => [ // Wednesday
                'breakfast' => ['Oats, cooked', 'Milk powder fortifier', 'Banana'],
                'snack1'    => ['Low-fat yoghurt, plain'],
                'lunch'     => ['Mince, lean', 'Pasta, cooked', 'Cheese, cheddar', 'Lettuce/salad'],
                'snack2'    => ['Amasi, plain', 'Peanuts'],
                'dinner'    => ['Chicken, skinless', 'Samp, cooked', 'Sugar beans, cooked'],
            ],
            3 => [ // Thursday
                'breakfast' => ['Weet-Bix', 'Full cream milk', 'Egg'],
                'snack1'    => ['Banana', 'Full cream milk'],
                'lunch'     => ['Beef stew meat', 'Rice, cooked', 'Cabbage'],
                'snack2'    => ['Cheese, cheddar', 'Brown/whole wheat bread'],
                'dinner'    => ['Fish, grilled', 'Pap, stiff', 'Spinach/morogo'],
            ],
            4 => [ // Friday
                'breakfast' => ['Soft maize porridge', 'Milk powder fortifier'],
                'snack1'    => ['Low-fat yoghurt, plain', 'Banana'],
                'lunch'     => ['Chicken, skinless', 'Potato', 'Lettuce/salad', 'Mixed vegetables'],
                'snack2'    => ['Cream crackers', 'Peanut butter'],
                'dinner'    => ['Mince, lean', 'Pasta, cooked', 'Mixed vegetables'],
            ],
            5 => [ // Saturday
                'breakfast' => ['Egg', 'Cheese, cheddar', 'Brown/whole wheat bread'],
                'snack1'    => ['Amasi, plain', 'Apple'],
                'lunch'     => ['Chicken, skinless', 'Rice, cooked', 'Butternut'],
                'snack2'    => ['Peanuts', 'Low-fat yoghurt, plain'],
                'dinner'    => ['Sugar beans, cooked', 'Pap, stiff'],
            ],
            6 => [ // Sunday
                'breakfast' => ['Oats, cooked', 'Peanut butter', 'Full cream milk'],
                'snack1'    => ['Banana', 'Full cream milk'],
                'lunch'     => ['Chicken, skinless', 'Pap, stiff', 'Cabbage', 'Carrots'],
                'snack2'    => ['Cheese, cheddar', 'Brown/whole wheat bread'],
                'dinner'    => ['Fish, grilled', 'Sweet potato', 'Spinach/morogo'],
            ],
        ],
    ],

];

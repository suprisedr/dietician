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

];

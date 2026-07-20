<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $map = [
            'Fruit & Vegetables'         => 'Fruit',
            'Starchy Foods'              => 'Starch',
            'Protein'                    => 'Protein - Animal',
            'Milk & Dairy'               => 'Dairy',
            'Spreading Fat, Oil & Sauce' => 'Fat',
            'Fat & Oil'                  => 'Fat',
            'Other'                      => 'Other/Limit',
        ];

        foreach ($map as $old => $new) {
            DB::table('meal_items')
                ->where('category', $old)
                ->update(['category' => $new]);
        }
    }

    public function down(): void
    {
        $map = [
            'Fruit'            => 'Fruit & Vegetables',
            'Starch'           => 'Starchy Foods',
            'Protein - Animal' => 'Protein',
            'Dairy'            => 'Milk & Dairy',
            'Fat'              => 'Spreading Fat, Oil & Sauce',
            'Other/Limit'      => 'Other',
        ];

        foreach ($map as $old => $new) {
            DB::table('meal_items')
                ->where('category', $old)
                ->update(['category' => $new]);
        }
    }
};

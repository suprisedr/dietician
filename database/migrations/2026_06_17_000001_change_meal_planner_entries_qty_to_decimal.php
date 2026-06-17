<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow fractional servings (e.g. 0.5, 1.5, 2.25) on meal planner entries
     * so nutritional totals scale proportionally.
     */
    public function up(): void
    {
        Schema::table('meal_planner_entries', function (Blueprint $table) {
            $table->decimal('qty', 6, 2)->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('meal_planner_entries', function (Blueprint $table) {
            $table->unsignedSmallInteger('qty')->default(1)->change();
        });
    }
};

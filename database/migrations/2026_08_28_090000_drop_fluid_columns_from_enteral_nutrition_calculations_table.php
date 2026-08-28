<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enteral feed calculations no longer take fluid requirements into account —
     * only the prescribed water flush counts as water given.
     */
    public function up(): void
    {
        Schema::table('enteral_nutrition_calculations', function (Blueprint $table) {
            $table->dropColumn([
                'fluid_requirement_ml',
                'free_water_from_formula_ml',
                'additional_water_ml',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('enteral_nutrition_calculations', function (Blueprint $table) {
            $table->decimal('fluid_requirement_ml', 8, 2)->nullable()->after('rate_ml_per_hour');
            $table->decimal('free_water_from_formula_ml', 8, 2)->nullable()->after('fluid_requirement_ml');
            $table->decimal('additional_water_ml', 8, 2)->nullable()->after('free_water_from_formula_ml');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enteral_nutrition_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Optional label for this calculation (e.g. "Day 1 Admission", "Week 2 Review")
            $table->string('label')->nullable();

            // Clinical condition driving energy & protein targets
            $table->string('clinical_condition')->default('standard');

            // Weight used for calculation
            $table->enum('weight_type', ['actual', 'ibw', 'abw'])->default('actual');
            $table->decimal('weight_kg', 6, 2);

            // Energy inputs & outputs
            $table->decimal('energy_kcal_per_kg', 5, 2);
            $table->decimal('energy_target_kcal', 8, 2);

            // Protein inputs & outputs
            $table->decimal('protein_g_per_kg', 5, 2);
            $table->decimal('protein_target_g', 8, 2);

            // Formula
            $table->decimal('formula_density', 3, 1); // 1.0, 1.2, or 1.5 kcal/mL
            $table->unsignedTinyInteger('feeding_hours_per_day')->default(24);

            // Calculated outputs
            $table->decimal('daily_volume_ml', 8, 2);
            $table->decimal('rate_ml_per_hour', 7, 2);
            $table->decimal('fluid_requirement_ml', 8, 2); // 30–35 mL/kg/day total fluid
            $table->decimal('free_water_from_formula_ml', 8, 2); // free water in formula
            $table->decimal('additional_water_ml', 8, 2);         // extra water to prescribe

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enteral_nutrition_calculations');
    }
};

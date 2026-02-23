<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('macronutrients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('type'); // e.g., 'carbohydrates', 'protein', 'fats'
            $table->float('range_min');
            $table->float('range_max');
            $table->float('selected_percentage');
            $table->float('kj');
            $table->float('grams');
            $table->timestamps();
        });

        // add exchange_template_id to patients so each patient can be linked to a template
        if (Schema::hasTable('patients') && !Schema::hasColumn('patients', 'exchange_template_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->foreignId('exchange_template_id')->nullable()->constrained('exchange_templates')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('macronutrients');
    }
};

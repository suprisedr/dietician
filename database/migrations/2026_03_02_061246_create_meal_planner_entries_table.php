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
        Schema::create('meal_planner_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('week_id')->constrained('meal_planner_weeks')->cascadeOnDelete();
            $table->tinyInteger('day_of_week'); // 0=Mon … 6=Sun
            $table->string('meal_slot');        // breakfast, snack1, lunch, snack2, dinner, snack3
            $table->string('meal_text')->nullable();
            $table->foreignId('meal_item_id')->nullable()->constrained('meal_items')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['week_id', 'day_of_week', 'meal_slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_planner_entries');
    }
};

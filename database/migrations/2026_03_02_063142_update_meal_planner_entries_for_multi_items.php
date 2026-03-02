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
        Schema::table('meal_planner_entries', function (Blueprint $table) {
            $table->unsignedSmallInteger('sort_order')->default(0)->after('meal_slot');
        });
    }

    public function down(): void
    {
        Schema::table('meal_planner_entries', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};

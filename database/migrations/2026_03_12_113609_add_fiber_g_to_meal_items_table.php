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
        Schema::table('meal_items', function (Blueprint $table) {
            $table->decimal('fiber_g', 6, 2)->nullable()->after('fat_g');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_items', function (Blueprint $table) {
            $table->dropColumn('fiber_g');
        });
    }
};

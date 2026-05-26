<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enteral_nutrition_calculations', function (Blueprint $table) {
            $table->integer('water_flush_ml')->default(30)->after('additional_water_ml');
            $table->string('water_flush_frequency', 20)->default('6-hourly')->after('water_flush_ml');
        });
    }

    public function down(): void
    {
        Schema::table('enteral_nutrition_calculations', function (Blueprint $table) {
            $table->dropColumn(['water_flush_ml', 'water_flush_frequency']);
        });
    }
};

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
        Schema::table('food_diaries', function (Blueprint $table) {
            $table->string('patient_token', 64)->nullable()->unique()->after('improvement');
            $table->timestamp('submitted_at')->nullable()->after('patient_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_diaries', function (Blueprint $table) {
            $table->dropColumn(['patient_token', 'submitted_at']);
        });
    }
};

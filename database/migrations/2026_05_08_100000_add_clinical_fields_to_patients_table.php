<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->text('allergies')->nullable()->after('reason_for_assessment');
            $table->text('medical_history')->nullable()->after('allergies');
            $table->text('medications')->nullable()->after('medical_history');
            $table->text('dietary_history')->nullable()->after('medications');
            $table->enum('appetite', ['good', 'fair', 'poor'])->nullable()->after('dietary_history');
            $table->string('referred_by', 255)->nullable()->after('appetite');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['allergies', 'medical_history', 'medications', 'dietary_history', 'appetite', 'referred_by']);
        });
    }
};

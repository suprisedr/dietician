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
        Schema::table('patients', function (Blueprint $table) {
            $table->enum('consent_status', ['pending', 'consented', 'declined'])
                  ->default('pending')
                  ->after('weekly_reminder_enabled');
            $table->string('consent_token', 64)->nullable()->unique()->after('consent_status');
            $table->timestamp('consent_token_expires_at')->nullable()->after('consent_token');
            $table->timestamp('consented_at')->nullable()->after('consent_token_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['consent_status', 'consent_token', 'consent_token_expires_at', 'consented_at']);
        });
    }
};

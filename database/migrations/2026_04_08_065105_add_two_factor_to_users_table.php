<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // The encrypted TOTP secret — null means 2FA not yet configured
            $table->text('two_factor_secret')->nullable()->after('password');
            // JSON array of single-use recovery codes
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            // When 2FA was confirmed/enabled
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            // When the user was first prompted to set up 2FA (for the 15-day skip window)
            $table->timestamp('two_factor_prompted_at')->nullable()->after('two_factor_confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'two_factor_prompted_at',
            ]);
        });
    }
};

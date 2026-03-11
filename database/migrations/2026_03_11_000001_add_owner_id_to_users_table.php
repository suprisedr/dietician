<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // NULL  = this user owns their own subscription
            // SET   = this user was invited by another user (the owner)
            $table->foreignId('owner_id')
                  ->nullable()
                  ->after('pricing_package_slug')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\User::class, 'owner_id');
            $table->dropColumn('owner_id');
        });
    }
};

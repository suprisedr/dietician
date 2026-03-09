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
        Schema::create('pricing_packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();          // 'free', 'package_1', etc.
            $table->string('name');                    // 'Free', 'Package 1', etc.
            $table->string('badge_label');             // badge text
            $table->string('badge_style');             // 'free' | 'popular' | 'pro' | 'team'
            $table->unsignedInteger('price_zar');      // monthly price in ZAR (0 = free)
            $table->string('tagline');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('max_users')->default(1);
            $table->unsignedInteger('group_price_zar')->nullable(); // e.g. 1800 for 5-user
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->json('features');                  // ordered array of feature strings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_packages');
    }
};

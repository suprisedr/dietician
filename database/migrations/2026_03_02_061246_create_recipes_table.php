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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('ingredients')->nullable();
            $table->text('directions')->nullable();
            $table->string('category')->nullable(); // e.g. Main Course, Dessert
            $table->integer('servings')->nullable();
            $table->integer('prep_time_min')->nullable();
            $table->integer('cook_time_min')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};

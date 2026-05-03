<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old recipes table if it has a different schema (pre-FatSecret version)
        Schema::dropIfExists('patient_recipe');
        Schema::dropIfExists('recipes');

        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('fatsecret_recipe_id')->nullable()->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('source_url')->nullable();
            $table->string('serving_size')->nullable();
            $table->decimal('calories', 8, 2)->nullable();
            $table->decimal('fat_g', 8, 2)->nullable();
            $table->decimal('carbs_g', 8, 2)->nullable();
            $table->decimal('protein_g', 8, 2)->nullable();
            $table->decimal('fiber_g', 8, 2)->nullable();
            $table->json('ingredients')->nullable();   // array of strings
            $table->text('directions')->nullable();
            $table->timestamps();
        });

        Schema::create('patient_recipe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_recipe');
        Schema::dropIfExists('recipes');
    }
};

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
        Schema::create('meal_items', function (Blueprint $table) {
            $table->id();
            $table->string('category');          // e.g. Fruit & Veg, Starchy, Protein …
            $table->string('name');              // display name
            $table->string('serving_size')->nullable();  // "One slice, about 5cm thick (80g)"
            $table->decimal('cho_g',   6, 2)->nullable(); // carbohydrate grams per serving
            $table->decimal('protein_g', 6, 2)->nullable();
            $table->decimal('fat_g',     6, 2)->nullable();
            $table->decimal('energy_kj', 8, 2)->nullable(); // kJ per serving
            $table->decimal('energy_kcal', 8, 2)->nullable();
            $table->unsignedTinyInteger('fruit_veg_portions')->default(0); // the +1/+2 values
            $table->boolean('is_system')->default(true);  // false = user-created custom
            $table->unsignedBigInteger('created_by')->nullable(); // user_id for custom items
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_items');
    }
};

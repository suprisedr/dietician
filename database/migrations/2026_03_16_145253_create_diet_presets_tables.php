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
        Schema::create('diet_presets', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();          // 'balanced', 'diabetes', 'renal'
            $table->string('name');
            $table->string('description');
            $table->unsignedSmallInteger('kcal_target')->default(0);
            $table->timestamps();
        });

        Schema::create('diet_preset_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diet_preset_id')->constrained()->cascadeOnDelete();
            $table->string('name');                   // matches exchange_template_items.name
            $table->unsignedSmallInteger('nu')->default(0);
            $table->float('cho_g')->nullable();
            $table->float('protein_min_g')->nullable();
            $table->float('protein_max_g')->nullable();
            $table->float('fat_min_g')->nullable();
            $table->float('fat_max_g')->nullable();
            $table->unsignedSmallInteger('kj')->nullable();
            $table->float('slot_breakfast')->default(0);
            $table->float('slot_snack1')->default(0);
            $table->float('slot_lunch')->default(0);
            $table->float('slot_snack2')->default(0);
            $table->float('slot_supper')->default(0);
            $table->float('slot_snack3')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_preset_items');
        Schema::dropIfExists('diet_presets');
    }
};


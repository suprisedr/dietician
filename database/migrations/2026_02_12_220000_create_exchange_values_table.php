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
        Schema::create('exchange_values', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('nu')->default(1);
            $table->integer('cho_g')->nullable();
            $table->integer('protein_min_g')->nullable();
            $table->integer('protein_max_g')->nullable();
            $table->integer('fat_min_g')->nullable();
            $table->integer('fat_max_g')->nullable();
            $table->integer('kj')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_values');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_template_id')->constrained('exchange_templates')->onDelete('cascade');
            $table->string('name');
            $table->integer('nu')->default(0);
            $table->integer('cho_g')->nullable();
            $table->integer('protein_min_g')->nullable();
            $table->integer('protein_max_g')->nullable();
            $table->integer('fat_min_g')->nullable();
            $table->integer('fat_max_g')->nullable();
            $table->integer('kj')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_template_items');
    }
};

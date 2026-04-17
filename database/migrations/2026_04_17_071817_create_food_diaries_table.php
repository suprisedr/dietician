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
        Schema::create('food_diaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $table->date('diary_date');
            $table->text('breakfast')->nullable();
            $table->text('snack1')->nullable();
            $table->text('lunch')->nullable();
            $table->text('snack2')->nullable();
            $table->text('supper')->nullable();
            $table->text('snack3')->nullable();
            $table->unsignedTinyInteger('rating')->nullable(); // 1–5
            $table->text('improvement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_diaries');
    }
};

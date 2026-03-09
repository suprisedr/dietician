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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->unique();          // Laravel session ID
            $table->string('device_name');                   // e.g. "Chrome on macOS"
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('last_active_at')->nullable(); // refreshed on each request
            $table->timestamp('logged_in_at')->useCurrent();
            $table->boolean('is_current')->default(false);   // flag set per request
            $table->timestamps();

            $table->index(['user_id', 'last_active_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};

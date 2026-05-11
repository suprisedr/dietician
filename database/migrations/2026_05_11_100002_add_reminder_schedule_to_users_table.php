<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 0 = Sunday … 6 = Saturday (matches Carbon::dayOfWeek)
            $table->tinyInteger('reminder_send_day')->default(1)->after('pricing_package_slug');
            // Hour of day in 24-h format (0–23)
            $table->tinyInteger('reminder_send_hour')->default(8)->after('reminder_send_day');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reminder_send_day', 'reminder_send_hour']);
        });
    }
};

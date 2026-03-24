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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('email')->nullable()->after('surname');
            $table->string('id_type', 20)->nullable()->after('email');       // 'sa_id' | 'passport'
            $table->string('id_number', 50)->nullable()->after('id_type');
            $table->date('date_of_birth')->nullable()->after('id_number');
            $table->text('address')->nullable()->after('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['email', 'id_type', 'id_number', 'date_of_birth', 'address']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->boolean('oedema')->default(false)->after('consent_token_expires_at');
            $table->timestamp('oedema_changed_at')->nullable()->after('oedema');
        });

        Schema::table('patient_visits', function (Blueprint $table) {
            $table->boolean('oedema')->nullable()->after('notes');
            $table->timestamp('oedema_changed_at')->nullable()->after('oedema');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['oedema', 'oedema_changed_at']);
        });

        Schema::table('patient_visits', function (Blueprint $table) {
            $table->dropColumn(['oedema', 'oedema_changed_at']);
        });
    }
};

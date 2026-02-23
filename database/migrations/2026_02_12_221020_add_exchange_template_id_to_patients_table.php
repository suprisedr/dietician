<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (! Schema::hasColumn('patients', 'exchange_template_id')) {
                $table->foreignId('exchange_template_id')->nullable()->constrained('exchange_templates')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'exchange_template_id')) {
                $table->dropForeign(['exchange_template_id']);
                $table->dropColumn('exchange_template_id');
            }
        });
    }
};

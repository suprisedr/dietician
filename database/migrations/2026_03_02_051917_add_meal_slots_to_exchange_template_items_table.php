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
        Schema::table('exchange_template_items', function (Blueprint $table) {
            $table->decimal('slot_breakfast', 5, 2)->default(0)->after('nu');
            $table->decimal('slot_snack1',    5, 2)->default(0)->after('slot_breakfast');
            $table->decimal('slot_lunch',     5, 2)->default(0)->after('slot_snack1');
            $table->decimal('slot_snack2',    5, 2)->default(0)->after('slot_lunch');
            $table->decimal('slot_supper',    5, 2)->default(0)->after('slot_snack2');
            $table->decimal('slot_snack3',    5, 2)->default(0)->after('slot_supper');
        });
    }

    public function down(): void
    {
        Schema::table('exchange_template_items', function (Blueprint $table) {
            $table->dropColumn(['slot_breakfast','slot_snack1','slot_lunch','slot_snack2','slot_supper','slot_snack3']);
        });
    }
};

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
        Schema::table('pricing_packages', function (Blueprint $table) {
            // Paystack plan code created via API (e.g. PLN_xxxxxxxxxx)
            $table->string('paystack_plan_code')->nullable()->after('sort_order');
            // Amount in kobo (ZAR cents × 100) — Paystack uses minor currency unit
            $table->unsignedBigInteger('paystack_amount')->nullable()->after('paystack_plan_code');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_packages', function (Blueprint $table) {
            $table->dropColumn(['paystack_plan_code', 'paystack_amount']);
        });
    }
};

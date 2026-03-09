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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('pricing_package_slug');
            $table->foreign('pricing_package_slug')
                  ->references('slug')->on('pricing_packages');

            // Paystack identifiers
            $table->string('paystack_subscription_code')->nullable()->unique();
            $table->string('paystack_customer_code')->nullable();
            $table->string('paystack_plan_code')->nullable();
            $table->string('paystack_email_token')->nullable(); // for manage-subscription link
            $table->string('paystack_authorization_code')->nullable();

            // Status: active | non_renewing | cancelled | expired | pending
            $table->string('status')->default('pending');

            $table->unsignedInteger('amount_zar')->default(0);  // actual charged amount
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('next_payment_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('ends_at')->nullable();           // hard expiry

            $table->json('last_event_payload')->nullable();     // raw webhook for auditing

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

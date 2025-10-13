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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_customer_id')->unique();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_session_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'cancelled', 'past_due', 'unpaid'])->default('active');
            $table->enum('payment_status', ['paid', 'unpaid', 'no_payment_required'])->default('unpaid');
            $table->integer('amount_total')->nullable(); // Amount in cents
            $table->string('currency', 3)->default('huf');
            $table->timestamp('subscription_started_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->timestamp('last_payment_at')->nullable();
            $table->timestamp('next_payment_at')->nullable();
            $table->json('metadata')->nullable(); // For storing custom Stripe metadata
            $table->json('customer_details')->nullable(); // For storing customer address, phone, etc.
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['stripe_customer_id']);
            $table->index(['email']);
            $table->index(['status']);
            $table->index(['payment_status']);
            $table->index(['subscription_ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};

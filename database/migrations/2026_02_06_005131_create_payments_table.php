<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();

            $table->decimal('amount', 10, 2);
            $table->decimal('consultation_fee', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->decimal('doctor_amount', 10, 2);
            $table->decimal('actual_payout_amount', 10, 2)->nullable();
            $table->decimal('platform_amount', 10, 2);
            $table->string('currency', 3)->default('EGP');

            $table->string('payment_method');
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded',])->default('pending');

            $table->enum('payout_status', ['pending', 'processing', 'paid', 'failed',])->default('pending');
            $table->string('payout_transaction_id')->nullable()->unique();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('payout_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('refund_type')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('payout_status');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

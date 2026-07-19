<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_commissions', function (Blueprint $table) {

            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('consultation_fee', 10, 2);
            $table->decimal('commission_rate', 5, 2)->default(10.00); // 10%
            $table->decimal('commission_amount', 10, 2);
            $table->enum('type', ['video', 'in_person']);
            $table->enum('status', ['pending', 'invoiced', 'paid'])->default('pending');
            $table->timestamp('invoiced_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['doctor_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_commissions');
    }
};

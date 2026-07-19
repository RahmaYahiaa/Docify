<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('slot_id')
                ->unique()
                ->constrained('doctor_availability_slots')
                ->cascadeOnDelete();

            $table->enum('type', ['video', 'in_person']);
            $table->enum('status', ['confirmed', 'completed', 'cancelled', 'no_show']);

            $table->text('reason_for_visit')->nullable();
            $table->text('notes')->nullable();

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->text('cancel_notes')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();

            $table->softDeletes();

            $table->index(['patient_id', 'status']);
            $table->index(['doctor_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

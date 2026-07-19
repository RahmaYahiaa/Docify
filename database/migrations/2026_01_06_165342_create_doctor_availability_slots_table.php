<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_availability_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->unsignedSmallInteger('duration_minutes')->default(30);
            $table->enum('type', ['video', 'in_person']);
            $table->foreignId('clinic_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['available', 'locked', 'booked', 'blocked'])->default('available');
            $table->timestamp('locked_until')->nullable();
            $table->timestamps();

            $table->unique(['doctor_id', 'date', 'start_time', 'type']);
            $table->index(['doctor_id', 'date', 'type']);
            $table->index(['date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_availability_slots');
    }
};

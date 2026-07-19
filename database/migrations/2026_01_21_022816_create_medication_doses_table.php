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
        Schema::create('medication_doses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_id')->constrained('medications')->cascadeOnDelete();
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->nullOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('prescription_item_id')->constrained('prescription_items')->cascadeOnDelete();
            $table->timestamp('dose_time');
            $table->string('amount');
            $table->boolean('taken')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_doses');
    }
};

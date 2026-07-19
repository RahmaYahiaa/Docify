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
      Schema::create('prescription_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
    $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
    $table->text('note')->nullable();
    $table->string('status')->default('pending');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_requests');
    }
};

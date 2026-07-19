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
        Schema::create('patient_chronic_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_profile_id')->constrained('patient_profiles')->cascadeOnDelete();
            $table->foreignId('chronic_condition_id')->constrained('chronic_conditions')->cascadeOnDelete();
            // $table->unique(['patient_profile_id', 'chronic_condition_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_chronic_conditions');
    }
};

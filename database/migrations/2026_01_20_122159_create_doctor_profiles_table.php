<?php

use App\Models\Specialization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();
            $table->foreignIdFor(Specialization::class)->nullable()->constrained('specializations')->cascadeOnDelete();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->cascadeOnDelete();
            $table->text('about')->nullable();
            $table->unsignedInteger('years_of_experience')->nullable();
            $table->decimal('video_fee', 8, 2)->nullable();
            $table->decimal('in_person_fee', 8, 2)->nullable();
            $table->json('languages')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};

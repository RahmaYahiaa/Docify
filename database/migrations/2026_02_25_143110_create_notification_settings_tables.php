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
        Schema::create('user_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('notification_type');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('user_delivery_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->boolean('push_enabled')->default(true);
            $table->boolean('in_app_enabled')->default(true);
            $table->boolean('email_enabled')->default(false);
            $table->boolean('dnd_enabled')->default(false)->index(); // Do Not Disturb
            $table->time('dnd_start')->default('22:00');
            $table->time('dnd_end')->default('08:00');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_delivery_preferences');
        Schema::dropIfExists('user_notification_settings');
    }
};

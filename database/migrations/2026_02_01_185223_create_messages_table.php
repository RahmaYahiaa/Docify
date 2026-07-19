<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('body')->nullable();
            $table->string('type')->default('text');
            $table->string('attachment_url')->nullable();
            $table->string('attachment_type')->nullable();
            $table->string('attachment_name')->nullable();
            $table->unsignedBigInteger('attachment_size')->nullable();
            $table->unsignedInteger('voice_duration')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['conversation_id', 'created_at']);
            $table->index('sender_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

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
        if (!Schema::hasColumn('reviews', 'appointment_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->foreignId('appointment_id')
                    ->after('id')
                    ->constrained()
                    ->cascadeOnDelete()
                    ->unique();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('appointment_id');
        });
    }
};

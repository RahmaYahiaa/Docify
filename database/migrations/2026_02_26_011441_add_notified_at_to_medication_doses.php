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
        Schema::table('medication_doses', function (Blueprint $table) {
            $table->timestamp('notified_at')->nullable()->after('taken')->index();
            $table->index(['dose_time', 'taken']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medication_doses', function (Blueprint $table) {
            $table->dropColumn('notified_at');
            $table->dropIndex(['dose_time', 'taken']);
        });
    }
};

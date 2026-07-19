<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      
        if (Schema::hasTable('prescription_items')) {


            if (!Schema::hasColumn('prescription_items', 'start_time')) {
                Schema::table('prescription_items', function (Blueprint $table) {
                    $table->timestamp('start_time')->nullable()->after('duration');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('prescription_items') &&
            Schema::hasColumn('prescription_items', 'start_time')) {

            Schema::table('prescription_items', function (Blueprint $table) {
                $table->dropColumn('start_time');
            });
        }
    }
};
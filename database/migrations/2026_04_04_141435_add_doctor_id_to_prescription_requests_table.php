<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() 
    {
        Schema::table('prescription_requests', function (Blueprint $table) {

            $table->foreignId('doctor_id')->nullable()->after('patient_id')->constrained('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('prescription_requests', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');
        });
    }
};
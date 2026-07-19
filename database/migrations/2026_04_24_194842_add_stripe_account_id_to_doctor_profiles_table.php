<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('doctor_profiles', function (Blueprint $table) {
        $table->string('stripe_account_id')->nullable()->after('user_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            //
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paypal_records', function (Blueprint $table) {
            $table->foreignId('credit_id')->nullable();
        });

        Schema::table('stripe_records', function (Blueprint $table) {
            $table->foreignId('credit_id');
        });

        Schema::table('elorus_records', function (Blueprint $table) {
            $table->foreignId('credit_id');
        });

        Schema::table('zoho_records', function (Blueprint $table) {
            $table->foreignId('credit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('4_tables', function (Blueprint $table) {
            //
        });
    }
};

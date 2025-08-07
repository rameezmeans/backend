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
        Schema::create('paypal_records', function (Blueprint $table) {
            $table->id();
            $table->string('paypal_id');
            $table->float('amount');
            $table->float('tax');
            $table->longText('desc');
            $table->timestamps();
        });

        Schema::create('stripe_records', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id');
            $table->float('amount');
            $table->float('tax');
            $table->longText('desc');
            $table->timestamps();
        });

        Schema::create('elorus_records', function (Blueprint $table) {
            $table->id();
            $table->string('elorus_id');
            $table->float('amount');
            $table->float('tax');
            $table->longText('desc');
            $table->timestamps();
        });

        Schema::create('zoho_records', function (Blueprint $table) {
            $table->id();
            $table->string('zoho_id');
            $table->float('amount');
            $table->float('tax');
            $table->longText('desc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('4_tables');
    }
};

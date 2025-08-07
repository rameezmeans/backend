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
        Schema::table('payment_accounts', function (Blueprint $table) {
            $table->string('senders_name')->nullable();
            $table->string('senders_phone_number')->nullable();;
            $table->string('senders_address')->nullable();;
            $table->string('companys_logo')->nullable();;
            $table->boolean('elorus')->default(false);
            $table->string('prefix')->nullable();
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_accounts', function (Blueprint $table) {
            //
        });
    }
};

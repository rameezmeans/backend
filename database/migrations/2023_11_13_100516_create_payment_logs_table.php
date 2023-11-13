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
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id');
            $table->foreignId('user_id');
            $table->string('elorus_id')->nullable();
            $table->string('reason_to_skip_elorus_id')->nullable();
            $table->string('zohobooks_id')->nullable();
            $table->string('reason_to_skip_zohobooks_id')->nullable();
            $table->boolean('email_sent')->default(0);
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
        Schema::dropIfExists('payment_logs');
    }
};

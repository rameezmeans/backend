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
        Schema::create('autotuner_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id');
            $table->integer('slave_id');
            $table->integer('ecu_id');
            $table->integer('model_id');
            $table->integer('mcu_id');
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
        Schema::dropIfExists('autotuner_data');
    }
};

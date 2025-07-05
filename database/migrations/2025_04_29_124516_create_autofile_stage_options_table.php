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
        Schema::create('auto_file_stage_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temporary_file_id');
            $table->foreignId('auto_searched_file_id');
            $table->foreignId('stage');
            $table->string('options');
            $table->integer('credits');
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
        //
    }
};
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
        Schema::create('file_event', function (Blueprint $table) {

            // $table->id();

            // $table->foreignId('temporary_file_id');
            // $table->time('file_uploaded', precision: 0);
            // $table->integer('credits_at_start');

            // $table->foreignId('stages')->default(0);
            // $table->string('options')->nullable();
            // $table->time('time_to_pick_stages_and_options', precision: 0)->nullable();
            // $table->integer('credits_at_stages_and_options')->default(0);
            // $table->integer('credits_for_stagees_and_options')->default(0);

            // $tab

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_event');
    }
};

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
        Schema::create('file_services', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('credits');
            $table->foreignId('service_id')->default(0);
            $table->foreignId('file_id')->default(0);
            $table->foreignId('temporary_file_id')->default(0);
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
        Schema::dropIfExists('file_services');
    }
};

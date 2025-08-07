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
        Schema::create('auto_file_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temporary_file_id');
            $table->foreignId('auto_searched_file_id');
            $table->string('brand');
            $table->string('model');
            $table->string('version');
            $table->string('engine');
            $table->boolean('is_modified')->default(0);
            $table->string('modification');
            $table->string('gearbox');
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
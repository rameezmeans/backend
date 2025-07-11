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
        Schema::create('reason_to_cancel', function (Blueprint $table) {
            $table->id();
            $table->string('reason_to_cancel');
            $table->timestamps();
        });

        Schema::create('file_reason_to_cancel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id');
            $table->string('reasons_to_cancel');
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

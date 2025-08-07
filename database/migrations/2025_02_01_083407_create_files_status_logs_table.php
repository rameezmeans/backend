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
        Schema::create('files_status_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('from');
            $table->string('to');
            $table->text('desc');
            $table->foreignId('file_id');
            $table->foreignId('changed_by');
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
        Schema::dropIfExists('files_status_logs');
    }
};

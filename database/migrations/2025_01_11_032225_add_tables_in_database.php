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
        Schema::table('modifications', function (Blueprint $table) {
            $table->string('name');
        });

        Schema::table('temporary_files', function (Blueprint $table) {
            $table->string('modification')->nullable();
            $table->string('mention_modification')->nullable();
        });

        Schema::table('files', function (Blueprint $table) {
            $table->string('modification')->nullable();
            $table->string('mention_modification')->nullable();
            $table->boolean('delayed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
};

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
        Schema::create('combinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('actual_credits')->default(0);
            $table->integer('discounted_credits')->default(0);
            $table->timestamps();
        });

        Schema::create('combination_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id');
            $table->foreignId('combination_id');
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
        Schema::dropIfExists('combinations');
    }
};

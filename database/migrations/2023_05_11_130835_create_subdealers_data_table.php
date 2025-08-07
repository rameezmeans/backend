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
        Schema::create('subdealers_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdealer_id');
            $table->string('backend_url')->nullable();
            $table->string('frontend_url')->nullable();
            $table->string('logo')->nullable();
            $table->string('colour_scheme')->nullable();
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
        Schema::dropIfExists('subdealers_data');
    }
};

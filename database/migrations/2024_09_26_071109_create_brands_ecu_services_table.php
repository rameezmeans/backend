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
        Schema::create('options_comments', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('ecu');
            $table->string('service_label');
            $table->string('software');
            $table->text('comments');
            $table->text('results');
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
        Schema::dropIfExists('brands_ecu_services');
    }
};

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
        Schema::create('temporary_files', function (Blueprint $table) {
            $table->id();

            $table->string('tool');
            $table->string('tool_type');
            $table->string('file_attached');
            $table->string('file_type');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('model_year');
            $table->string('license_plate');
            $table->string('vin_number');
            $table->string('brand');
            $table->string('model');
            $table->string('version');
            $table->string('engine');
            $table->string('tools');
            $table->string('ecu')->nullable();
            $table->string('gear_box')->nullable();

            $table->text('vehicle_internal_notes')->nullable();
            $table->text('customer_internal_notes')->nullable();
            $table->integer('kilometrage')->nullable();
            $table->string('first_registration')->nullable();

            $table->string('stages')->nullable();
            $table->text('options')->nullable();

            $table->integer('credits');
            $table->string('status')->default('submitted');
            $table->boolean('is_credited')->default(0);

            $table->integer('original_file_id')->unsigned()->nullable();
            $table->string('request_type')->nullable(); 

            $table->text('additional_comments')->nullable();

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
        Schema::dropIfExists('temporary_files');
    }
};

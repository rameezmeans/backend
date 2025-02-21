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
            $table->string('file_path');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('model_year')->nullable();
            $table->string('license_plate')->nullable();
            $table->string('vin_number')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('version')->nullable();
            $table->string('engine')->nullable();
            $table->string('tools')->nullable();
            $table->string('ecu')->nullable()->nullable();  
            $table->string('gear_box')->nullable();

            $table->text('vehicle_internal_notes')->nullable();
            $table->text('customer_internal_notes')->nullable();
            $table->integer('kilometrage')->nullable();
            $table->string('first_registration')->nullable();
            $table->string('dtc_off_comments')->nullable(); 

            $table->integer('credits')->default(0);
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

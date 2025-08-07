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
        Schema::create('alientech_test_folders', function (Blueprint $table) {

            $table->id();
            $table->string('customers_upload_guid');
            $table->string('slot_id');
            $table->string('customers_upload');
            $table->string('decoded1')->nullable();
            $table->string('decoded2')->nullable();
            $table->string('engineers_upload_guid')->nullable();
            $table->string('engineers_upload_encoded_guid')->nullable();
            $table->string('engineers_upload')->nullable();
            $table->string('engineers_upload_encoded')->nullable();
            $table->string('errors')->nullable();
            $table->boolean('success')->default(0);

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
        Schema::dropIfExists('alientech_test_folders');
    }
};

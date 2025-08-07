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
        Schema::table('request_files', function (Blueprint $table) {
            $table->boolean('is_kess3_slave')->default(0);
            $table->boolean('uploaded_successfully')->default(0);
            $table->boolean('encoded')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_files', function (Blueprint $table) {
            //
        });
    }
};

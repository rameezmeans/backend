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
        Schema::table('files', function (Blueprint $table) {
            $table->foreignId('front_end_id')->default(1)->Delete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('front_end_id')->default(1)->Delete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('tables', function (Blueprint $table) {
        //     //
        // });
    }
};

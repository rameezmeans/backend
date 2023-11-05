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
        Schema::table('users', function (Blueprint $table) {
            $table->string('zohobooks_id')->nullable();
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->string('zohobooks_id')->nullable();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->string('zohobooks_tax_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

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
        Schema::table('news_feed', function (Blueprint $table) {
            $table->foreignId('subdealer_group_id')->nullable();
        });

        Schema::table('message_templates', function (Blueprint $table) {
            $table->foreignId('subdealer_group_id')->nullable();
        });

        Schema::table('email_templates', function (Blueprint $table) {
            $table->foreignId('subdealer_group_id')->nullable();
        });

        Schema::table('reminder_manager', function (Blueprint $table) {
            $table->foreignId('subdealer_group_id')->nullable();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('subdealer_group_id')->nullable();
        });

        Schema::table('vehicle_notes', function (Blueprint $table) {
            $table->foreignId('subdealer_group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};

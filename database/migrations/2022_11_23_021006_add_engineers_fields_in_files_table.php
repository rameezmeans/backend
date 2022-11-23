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
            $table->timestamp('assignment_time')->nullable();
            $table->timestamp('reupload_time')->nullable();
            $table->integer('response_time')->nullable();
            // $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            // $table->renameColumn('user_id', 'assigned_to');
            $table->integer('assigned_to')->unsigned()->nullable();
            // $table->foreign('assigned_to')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            //
        });
    }
};

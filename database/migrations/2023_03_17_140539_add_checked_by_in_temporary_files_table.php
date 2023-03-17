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
        Schema::table('temporary_files', function (Blueprint $table) {
            $table->string('checked_by')->nullable();
            $table->unsignedInteger('assigned_to')->nullable();
            $table->string('file_path')->nullable();
            $table->string('dtc_off_comments')->nullable();

            $table->timestamp('assignment_time')->nullable();
            $table->timestamp('reupload_time')->nullable();
            $table->integer('response_time')->nullable();
            // $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            // $table->renameColumn('user_id', 'assigned_to');
            $table->string('support_status')->default('closed')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temporary_files', function (Blueprint $table) {
            //
        });
    }
};

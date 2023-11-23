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
        Schema::table('reminder_manager', function (Blueprint $table) {
            $table->boolean('eng_assign_admin_whatsapp')->default(0);
            $table->boolean('eng_assign_eng_whatsapp')->default(0);
            $table->boolean('eng_assign_cus_whatsapp')->default(0);
            $table->boolean('file_upload_admin_whatsapp')->default(0);
            $table->boolean('file_upload_eng_whatsapp')->default(0);
            $table->boolean('eng_file_upload_admin_whatsapp')->default(0);
            $table->boolean('eng_file_upload_cus_whatsapp')->default(0);
            $table->boolean('file_new_req_admin_whatsapp')->default(0);
            $table->boolean('file_new_req_eng_whatsapp')->default(0);
            $table->boolean('msg_cus_admin_whatsapp')->default(0);
            $table->boolean('msg_cus_eng_whatsapp')->default(0);
            $table->boolean('msg_eng_admin_whatsapp')->default(0);
            $table->boolean('msg_eng_cus_whatsapp')->default(0);
            $table->boolean('status_change_admin_whatsapp')->default(0);
            $table->boolean('status_change_eng_whatsapp')->default(0);
            $table->boolean('status_change_cus_whatsapp')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reminder_manager', function (Blueprint $table) {
            //
        });
    }
};

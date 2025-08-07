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
        Schema::create('download_lua_files', function (Blueprint $table) {

            $table->id();

            $table->string('request_file');
            $table->string('file_type');
            $table->string('ecu_file_select');
            $table->string('gearbox_file_select');
            $table->string('master_tools');
            $table->string('tool_type');
            $table->foreignId('file_id');
            $table->boolean('engineer')->default(0);
            $table->integer('visible')->default(1);
            $table->longText('lua_command')->nullable();
            $table->longText('lua_command_fdb')->nullable();
            $table->string('olsname')->nullable();
            $table->boolean('show_comments')->default(1);
            $table->boolean('is_kess3_slave')->default(0);
            $table->boolean('uploaded_successfully')->default(0);
            $table->boolean('encoded')->default(0);
            $table->boolean('comments_denied')->default(0);
            $table->boolean('show_file_denied')->default(0);

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
        Schema::dropIfExists('download_lua_files');
    }
};

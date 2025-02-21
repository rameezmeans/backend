<?php

use App\Models\Role;
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

            $table->unsignedBigInteger('subdealer_group_id')->nullable(); 

            $table->foreign('subdealer_group_id')
            ->references('id')->on('subdealer_groups')
            ->onDelete('set null');

            $role = Role::where('name', 'customer')->first();

            $table->unsignedBigInteger('role_id')->default($role->id); 

            $table->foreign('role_id')
            ->references('id')->on('roles')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::create('user_changes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('language')->nullable();
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('status')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_id')->nullable();
            $table->string('group_id')->nullable();
            $table->string('elorus_id')->nullable();
            $table->string('exclude_vat_check')->nullable();
            $table->string('evc_customer_id')->nullable();
            $table->string('evc_customer_id')->nullable();
            $table->string('mailchimp_id')->nullable();
            $table->string('zohobooks_id')->nullable();
            $table->boolean('test')->default(0);
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
        Schema::dropIfExists('user_changes');
    }
};

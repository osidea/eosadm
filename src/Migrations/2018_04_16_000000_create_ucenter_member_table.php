<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUcenterMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ucenter_member', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('username', 100)->unique();
            $table->string('phone', 11)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('created_at', 100)->nullable();
            $table->string('updated_at', 100)->nullable();
            $table->softDeletes();
            $table->rememberToken()->nullable();
            $table->string('status', 10);
            $table->string('auth')->default('--');
            /**
             * auth:
             * @admin
             * @develop
             */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ucenter_member');
    }
}

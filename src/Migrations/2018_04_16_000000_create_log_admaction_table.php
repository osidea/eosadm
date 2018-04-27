<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogAdmactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_admaction', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('uid')->default(0);
            $table->string('remote_addr')->nullable();
            $table->string('request_method')->nullable();
            $table->text('http_user_agent')->nullable();
            $table->string('created_at', 100)->nullable();
            $table->string('updated_at', 100)->nullable();
            $table->text('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_admaction');
    }
}

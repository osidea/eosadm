<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_rule', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('name', 100)->default('');
            $table->string('icon', 11)->nullable();
            $table->string('style_type', 1)->default(0);
            $table->integer('pid')->default(0);
            $table->string('c')->nullable();
            $table->string('f')->nullable();
            $table->string('o')->nullable();
            $table->string('to')->nullable();
            $table->string('created_at', 100)->nullable();
            $table->string('updated_at', 100)->nullable();
            $table->integer('auth')->default(0);
            $table->string('status', 10)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_rule');
    }
}

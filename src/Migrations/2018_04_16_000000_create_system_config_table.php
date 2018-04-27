<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_config', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('name', 200)->unique(); //调取名称
            $table->string('title', 200); //显示名称
            $table->string('group', 200); //分组
            $table->string('remark')->nullable(); //说明
            $table->tinyInteger('type')->default(0);
            $table->integer('sort')->default(0);
            $table->text('extra')->nullable();
            $table->text('value')->nullable();
            $table->string('created_at', 100)->nullable();
            $table->string('updated_at', 100)->nullable();
            $table->string('status', 10)->default(1);
            $table->string('lock', 10)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_config');
    }
}

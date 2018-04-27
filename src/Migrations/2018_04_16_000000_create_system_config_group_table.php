<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemConfigGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_config_group', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('name', 200); //
            $table->string('key', 200); //
            $table->string('created_at', 100)->nullable();
            $table->string('updated_at', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_config_group');
    }
}

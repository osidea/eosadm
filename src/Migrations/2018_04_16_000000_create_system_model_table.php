<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_model', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('name');
            $table->string('model_name')->unique();
            $table->string('action')->nullable();
            $table->string('done')->nullable();
            $table->string('created_at', 100)->nullable();
            $table->string('updated_at', 100)->nullable();
            $table->string('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_model');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemModelFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_model_field', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('model_name');
            $table->string('name');
            $table->string('field');
            $table->string('placeholder')->nullable();
            $table->string('default_value')->nullable();
            $table->text('extra_custom')->nullable();
            $table->string('extra_model')->nullable();
            $table->text('extra_where')->nullable();
            $table->string('extra_name')->nullable();
            $table->string('extra_value')->nullable();
            $table->string('remark')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('system_model_field');
    }
}

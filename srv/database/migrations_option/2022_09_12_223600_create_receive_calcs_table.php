<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiveCalcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_calcs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receive_id');
            $table->integer('frame_id');
            $table->string('column', 255);
            $table->string('calc', 20);
            $table->integer('created_id')->nullable();
            $table->string('created_name', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->integer('updated_id')->nullable();
            $table->string('updated_name', 255)->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->string('deleted_name', 255)->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receive_calcs');
    }
}

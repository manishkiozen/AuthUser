<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catlog', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('icon_file');
            $table->integer('catlog_id');
            $table->integer('position');
            $table->enum('status', array('0', '1'));
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
        Schema::drop('catlog');
    }
}

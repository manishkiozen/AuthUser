<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_voucher', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_code');
            $table->double('price');
            $table->integer('created_by');
            $table->integer('deleted_by');
            $table->enum('status', ['0','1']);
            $table->dateTime('used_at');
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
        Schema::drop('e_voucher');
    }
}

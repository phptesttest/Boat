<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('toinfos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('orderNub');
            $table->string('toNub');
            $table->string('time');
            $table->integer('state');
            $table->integer('type');
            $table->integer('wishid');
            $table->rememberToken();
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
        //
        Schema::drop('toinfos');
    }
}

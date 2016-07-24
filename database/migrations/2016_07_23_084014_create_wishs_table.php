<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWishsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('wishs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('orderNub');
            $table->string('toNub');
            $table->string('fromNub');
            $table->string('time');
            $table->integer('state');
            $table->integer('type');
            $table->string('fromname');
            $table->string('toname');
            $table->integer('distance');
            $table->integer('isopen');
            $table->integer('level');
            $table->string('photopath');
            $table->integer('isphotoopen');
            $table->string('text');
            $table->string('voice');
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
        Schema::drop('wishs');
    }
}

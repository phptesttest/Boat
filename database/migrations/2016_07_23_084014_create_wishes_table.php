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
            $table->integer('state')->default('0');
            $table->integer('type')->default('0');
            $table->string('fromname');
            $table->string('toname');
            $table->integer('distance')->default('0');
            $table->integer('isopen')->default('0');
            $table->integer('level')->default('1');
            $table->string('photopath');
            $table->integer('isphotoopen')->default('0');
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

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrominfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('frominfos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('orderNub');
            $table->string('fromNub');
            $table->string('time');
            $table->string('number');
            $table->integer('type')->default('0');
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
        Schema::drop('frominfos');
    }
}

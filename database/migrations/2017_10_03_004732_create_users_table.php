<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('openid',30)->unique();
            $table->string('sessionkey',30)->nullable();
            $table->string('nickName',30)->nullable();
            $table->tinyInteger('gender')->default(0);
            $table->string('city',30)->nullable();
            $table->string('province',30)->nullable();
            $table->string('country',30)->nullable();
            $table->string('avatarUrl')->nullable();
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
        Schema::dropIfExists('users');
    }
}

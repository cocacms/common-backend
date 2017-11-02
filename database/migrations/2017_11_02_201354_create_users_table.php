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
            $table->increments('id');

            $table->string('username',18)->unique();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('sex')->default(0); //性别 0-未知 1-男 2-女
            $table->string('tel',11)->nullable(); //手机
            $table->string('mail',60)->nullable(); //邮箱
            $table->date('birthday')->nullable(); //生日
            $table->string('nickname',20)->nullable(); //昵称
            $table->softDeletes();
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

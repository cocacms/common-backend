<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('username',18)->unique();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->string('avatar')->default('images/avatar.png');
            $table->integer('sex')->default(0); //性别 0-未知 1-男 2-女
            $table->string('tel',11)->nullable(); //手机
            $table->string('mail',60)->nullable(); //邮箱
            $table->date('birthday')->nullable(); //生日
            $table->string('nickname',20)->nullable(); //昵称
            $table->integer('supper')->default(0); //超级管理员 0 不是 1 是
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
        Schema::dropIfExists('members');
    }
}

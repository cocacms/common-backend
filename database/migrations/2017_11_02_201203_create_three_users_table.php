<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('three_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('uid')->nullable();
            $table->string('openid',30)->unique();
            $table->string('sessionkey',30)->nullable();
            $table->string('nickName',30)->nullable();
            $table->tinyInteger('gender')->default(0);
            $table->string('city',30)->nullable();
            $table->string('province',30)->nullable();
            $table->string('country',30)->nullable();
            $table->string('avatarUrl')->nullable();
            $table->text('phoneDetail')->nullable();
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
        Schema::dropIfExists('three_users');
    }
}

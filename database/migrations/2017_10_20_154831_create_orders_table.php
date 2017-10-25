<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('oid')->unique(); //订单id
            $table->integer('creator'); //购买者
            $table->integer('seller'); //出售者

            $table->string('userName')->nullable(); //收货人姓名
            $table->string('postalCode')->nullable(); //邮编
            $table->string('provinceName')->nullable(); //国标收货地址第一级地址
            $table->string('cityName')->nullable(); //国标收货地址第二级地址
            $table->string('countyName')->nullable(); //国标收货地址第三级地址
            $table->string('detailInfo')->nullable(); //详细收货地址信息
            $table->string('nationalCode')->nullable(); //详细收货地址信息
            $table->string('telNumber')->nullable(); //手机

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
        Schema::dropIfExists('orders');
    }
}

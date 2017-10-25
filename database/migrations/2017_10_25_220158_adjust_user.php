<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
//            brand	手机品牌	1.5.0
//            model	手机型号
//            pixelRatio	设备像素比
//            screenWidth	屏幕宽度	1.1.0
//            screenHeight	屏幕高度	1.1.0
//            windowWidth	可使用窗口宽度
//            windowHeight	可使用窗口高度
//            language	微信设置的语言
//            version	微信版本号
//            system	操作系统版本
//            platform	客户端平台
//            fontSizeSetting	用户字体大小设置。以“我-设置-通用-字体大小”中的设置为准，单位：px	1.5.0
//            SDKVersion	客户端基础库版本	1.1.0
            $table->text('phoneDetail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn('phoneDetail');
        });
    }
}

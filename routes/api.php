<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::options('{all?}', 'UploadController@index');

Route::middleware(['auth:apiFront'])->group(function (){

    /**
     * 小程序
     */
    Route::prefix('xcx')->namespace('Xcx')->group(function (){
        Route::prefix('user')->group(function (){
            Route::post('/update', 'AuthController@updateInfo');
        });
    });


    Route::post('/upload', 'UploadController@upload');

});

/**
 * 小程序
 */
Route::prefix('xcx')->namespace('Xcx')->group(function (){
    Route::post('/login', 'AuthController@login');
    Route::get('/wx_access_token', 'AuthController@wx_access_token');
});



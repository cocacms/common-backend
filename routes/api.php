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

Route::options('{all?}', 'OptionsController@index');

Route::middleware(['auth:apiFront'])->group(function (){
    Route::prefix('user')->group(function (){
        Route::post('/update', 'AuthController@updateInfo');
    });


    Route::prefix('activity')->group(function (){
        Route::get('/', 'ActivityController@my');
        Route::get('/{id}', 'ActivityController@detail');
        Route::post('/', 'ActivityController@create');

        Route::put('/', 'ActivityController@handle');
    });

    Route::prefix('order')->group(function (){
        Route::get('/sell', 'OrderController@sell');
        Route::get('/buy', 'OrderController@buy');
        Route::get('/check/{tid}', 'OrderController@check');
    });

    Route::prefix('good')->group(function (){
        Route::get('/', 'GoodController@my');
        Route::get('/search', 'GoodController@search');
        Route::get('/byIds', 'GoodController@byIds');
        Route::post('/', 'GoodController@create');
    });

    Route::post('/upload', 'UploadController@upload');

});

Route::get('/login', 'AuthController@login');


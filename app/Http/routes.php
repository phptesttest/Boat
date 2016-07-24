<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', function () {
    return view('admin.login');
});*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
    Route::get('/', 'AdminController@login');
    Route::post('/login', 'AdminController@loginDeal');
    
});

Route::group(['middleware' => ['web'],'prefix'=>'weixin'], function () {
    //
    
});

Route::group(['middleware' => ['web'],'prefix'=>'admin'], function () {
    //
    Route::get('/import', 'AdminController@import');
    Route::post('/import', 'AdminController@importFun');
    Route::get('/index','AdminController@index');

    //送礼端模块
    Route::get('/giver/list','AdminController@givelist');
    Route::get('/giver/search','AdminController@givesearch');

    //获赠方模块
    Route::get('/receiver/list','AdminController@receivelist');
    Route::get('receiver/search','AdminController@receivesearch');

    //统计模块
    Route::get('/count/flow','AdminController@countflow');
    Route::get('count/wishrank','AdminController@countwishrank');

});

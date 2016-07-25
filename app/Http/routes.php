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
    Route::post('/index', 'AdminController@loginDeal');
    
});

Route::group(['middleware' => ['web'],'prefix'=>'weixin'], function () {
    //
    
});

Route::group(['middleware' => ['web'],'prefix'=>'admin'], function () {
    //
    Route::get('/import', 'AdminController@import');
    Route::get('/giveExport/{flag}', 'AdminController@giveExportFun');
    Route::get('/receiveExport/{flag}', 'AdminController@receiveExportFun');
    Route::post('/import', 'AdminController@importFun');
    Route::get('/index','AdminController@index');
    Route::get('/logout','AdminController@logout');

    //送礼端模块
    Route::get('/giver/list','AdminController@givelist');
    Route::post('/giver/list','AdminController@givelist');
    Route::get('/giver/search','AdminController@givesearch');
    Route::post('/giver/search','AdminController@givesearchFun');

    //获赠方模块
    Route::get('/receiver/list','AdminController@receivelist');
    Route::post('/receiver/list','AdminController@receivelist');
    Route::get('receiver/search','AdminController@receivesearch');
    Route::post('receiver/search','AdminController@receivesearchFun');
    

    //统计模块
    Route::get('/count/flow','AdminController@countflow');
    Route::get('count/wishrank','AdminController@countwishrank');

    //列表详情页
    Route::get('receiver/pagelist/{id}','AdminController@pagedetail');

});

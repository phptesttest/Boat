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
    Route::get('/test', 'AdminController@test');
    
});

Route::group(['middleware' => ['web'],'prefix'=>'weixin'], function () {
    //获取验证码
    Route::get('/phoneTest', 'Weixin\FromController@phoneTest');
    //验证码验证
    Route::get('/vadliate', 'Weixin\FromController@validateFun');
    //获取祝福列表
    Route::get('/getLists', 'Weixin\FromController@getLists');
    //获取祝福详情
    Route::get('/getDetail', 'Weixin\FromController@getDetail');
    //获取祝福排行
    Route::get('/link', 'Weixin\FromController@link');
    //发起祝福
    Route::get('/createWish', 'Weixin\FromController@createWish');
    //验证签名
    Route::get('/getSignPackage', 'Weixin\FromController@getSignPackage'); 
    //网页授权   
    Route::get('/auth', 'Weixin\FromController@auth');
    //用户点赞，改变船的状态
    Route::get('/comeFun', 'Weixin\FromController@comeFun');
    //小船起航
    Route::get('/sail', 'Weixin\FromController@sail');
    //判断该用户是否可以点赞
    Route::get('/isCome', 'Weixin\FromController@isCome');

       
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
    Route::post('receiver/wishset/{id}','AdminController@wishset');
    

    //排行模块
    Route::get('count/wishrank','AdminController@countwishrank');

    //列表详情页
    Route::get('receiver/pagelist/{id}','AdminController@pagedetail');

});

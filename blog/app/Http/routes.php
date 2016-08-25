<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/wechat','Rocksea\WechatController@serve');
Route::get('/token','Rocksea\WechatController@getAccessToken');
Route::any('/material','Rocksea\WechatController@material');
Route::any('/forever','Rocksea\WechatController@addForeverImg');
Route::any('/addNews','Rocksea\WechatController@getNews');
route::any('/a','Rocksea\WechatController@addContentImg');



<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->name('api.v1.')->group(function() {
    //注册登录
    Route::resource('phone_code', 'Api\PhoneCodeController')->only('store');
    Route::resource('captcha', 'Api\CaptchaController')->only('store');
    Route::get('oauth/wechat', 'Api\WechatController@store')->name('wechat.store');
    Route::post('login', 'Api\LoginController@store')->name('login.store');

    //jwt
    Route::put('jwt_token', 'Api\JWTTokenController@update')->name('jwt_token.update');
    Route::delete('jwt_token', 'Api\JWTTokenController@destroy')->name('jwt_token.destroy');

    //用户信息
    Route::resource('users', 'Api\UserController')->only('store', 'show');
    Route::middleware('auth:api')->group(function() {
        Route::get('user', 'Api\UserController@me')->name('user.show');
        Route::patch('user', 'Api\UserController@update')->name('user.update');
        Route::post('user/avatar', 'Api\UserController@updateAvatar')->name('user.update.avatar');
    });

    //帖子相关
    Route::resource('cateogries', 'Api\CategoryController')->only('index');
    Route::resource('topics', 'Api\TopicController')->only('show', 'index');
    Route::get('users/{user}/topics', 'Api\TopicController@userIndex')->name('topics.user.index');
    Route::middleware('auth:api')->group(function() {
        Route::resource('topics', 'Api\TopicController')->only('store', 'update', 'destroy');
    });

    //帖子回复
    Route::resource('replies', 'Api\ReplyController')->only('index');
    Route::middleware('auth:api')->group(function() {
        Route::resource('replies', 'Api\ReplyController')->only('store', 'destroy');

        //回复消息通知
        Route::get('notifications', 'Api\NotificationController@show')->name('notification.show');
        Route::get('notifications/stats', 'Api\NotificationController@stats')->name('notification.stats');
        Route::patch('notifications/mark_read', 'Api\NotificationController@markRead')->name('notification.mark_read');
    });
});

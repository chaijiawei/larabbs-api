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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->name('api.v1.')->group(function() {
    Route::get('version', function() {
        return 'this is version 1';
    })->name('version');

    Route::resource('phone_code', 'Api\PhoneCodeController')->only('store');
    Route::resource('users', 'Api\UserController')->only('store');

    Route::resource('captcha', 'Api\CaptchaController')->only('store');

    Route::get('oauth/wechat', 'Api\WechatController@store')->name('wechat.store');

    Route::post('login', 'Api\LoginController@store')->name('login.store');

    Route::put('jwt_token', 'Api\JWTTokenController@update')->name('jwt_token.update');
    Route::delete('jwt_token', 'Api\JWTTokenController@destroy')->name('jwt_token.destroy');
});

Route::prefix('v2')->name('api.v2.')->group(function() {
    Route::get('version', function() {
        return 'this is version 2';
    })->name('version');
});

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

/**
 * WebHook相关路由
 * /api/webhook/*
 */
Route::group(['prefix' => 'webhook', 'as' => 'webhook.'], function () {
    require __DIR__ . '/modules/api/webhook.php';
});

/**
 * Wechat相关路由
 */
Route::group(['prefix' => 'wechat', 'as' => 'wechat.'], function () {
    require __DIR__ . '/modules/api/wechat.php';
});

// 小程序端的Protecting-routes
Route::middleware('auth:api')->group(function () {
});
// 后端的Protecting-routes
Route::middleware('auth:admin')->group(function () {
});

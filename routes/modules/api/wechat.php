<?php


use Illuminate\Support\Facades\Route as RouteAlias;

/**
 * 微信公众号 登录回调
 * GET /api/wechat/authenticate
 */
RouteAlias::get('authenticate', [\App\Http\Controllers\ThirdParty\WechatController::class, 'authenticate']);

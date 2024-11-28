<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 微信公众号控制器
 */
class WechatController extends Controller
{
    public function serve()
    {
        Log::info('request arrived.');

        $server = app('easywechat.official_account')->getServer();

        $server->with(function ($message) {
            return "欢迎关注！";
        });

        return $server->serve();
    }

    /**
     * 微信H5获取用户信息
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required'
        ], ['code.required' => 'code不能为空']);
        $oauth = app('easywechat.official_account')->getOauth();
        $user = $oauth->userFromCode($validated['code']);
        Log::info('登录获取到的用户信息为:', [
            'user' => $user
        ]);
        return redirect()->back();
    }
}

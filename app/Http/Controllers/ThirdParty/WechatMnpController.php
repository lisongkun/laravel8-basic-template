<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * 微信小程序控制器
 */
class WechatMnpController extends Controller
{

    /** @var \easywechat.mini_app|(\easywechat.mini_app&\Illuminate\Contracts\Foundation\Application)|\Illuminate\Contracts\Foundation\Application|mixed  */
    protected $mnpApp;

    public function __construct()
    {
        $this->mnpApp = app('easywechat.mini_app');
    }

    public function login(Request $request)
    {
        $data = $request->validate(['code' => 'required', 'nickname' => 'required', 'avatar' => 'required'],
            [
                'code.required' => '校验码不能为空',
                'nickname.required' => '用户昵称不能为空',
                'avatar.required' => '用户头像不能为空',
            ]);
        $utils = $this->mnpApp->getUtils();
        $response = $utils->codeToSession($data['code']); // openid,session_key
        // create Or update
        $foundUser = User::where('mnpOpenId', $response['openid'])->first();
        if ($foundUser) {
            $user = $foundUser;
            $user->update([
                'last_login_at' => \Carbon\Carbon::now(),
                'last_login_ip' => $request->getClientIp(),
            ]);
        } else {
            \Log::info('User not found, create new user.', [
                'mnpOpenId' => $response['openid'],
                'nickname' => $data['nickname'],
                'avatar' => $data['avatar'],
                'password' => \Hash::make($response['openid']),
                'last_login_at' => \Carbon\Carbon::now(),
                'last_login_ip' => $request->getClientIp(),
            ]);
            $user = User::create([
                'mnpOpenId' => $response['openid'],
                'nickname' => $data['nickname'],
                'avatar' => $data['avatar'],
                'password' => \Hash::make($response['openid']),
                'last_login_at' => \Carbon\Carbon::now(),
                'last_login_ip' => $request->getClientIp(),
            ]);
        }
        $token = $user->createToken(uniqid())->plainTextToken;
        return \Response::success(respondWithToken($token));
    }
}

<?php

namespace App\Http\Controllers\Deploy;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    /**
     * 监听Coding仓库代码更新操作
     * 自动部署最新版项目
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function coding(Request $request)
    {
        Log::info('接收到Coding WebHook', [$request]);
        $hookEvent = $request->header('X-Coding-Event');
        if (!\Str::contains($hookEvent, 'push')) return \Response::ok();
        Log::info("开始执行自动部署任务");
        // Git Pull
        exec('cd .. && sudo git pull' . ' 2>&1', $output, $status);
        if ($status != 0) {
            Log::error('拉取代码失败', [$output, $status]);
            \Response::fail('拉取代码失败', ResponseCodeEnum::SYSTEM_ERROR);
        }
        Log::info("拉取代码成功", [$output, $status]);
        // Composer Install
        exec('cd .. && /www/server/php/81/bin/php /usr/local/bin/composer install' . ' 2>&1', $output, $status);
        if ($status != 0) {
            Log::error('composer安装失败', [$output, $status]);
            \Response::fail('composer安装失败', ResponseCodeEnum::SYSTEM_ERROR);
        }
        Log::info("composer安装成功", [$output, $status]);
        // 后端完成部署
        Log::info("自动部署任务执行完毕");
        return \Response::ok();
    }
}

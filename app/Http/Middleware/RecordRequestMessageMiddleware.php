<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RecordRequestMessageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 记录所有请求信息
        $requestMessage = [
            'url' => $request->url(),
            'method' => $request->method(),
            'ip' => $request->ips(),
            'path' => $request->path(),
            'headers' => $request->header(),
            'query' => $request->query()
        ];

        if ($request->file()) {
            // 文件内容不做日志记录，使用<file>做标识
            $requestMessage['body'] = '<file>';
        } else {
            // 获取请求体Body信息
            $bodyContent = $request->all();
            // 从.env文件中获取参数内容的长度
            $parameterLength = \config('logging.channels.request.value_max_length');

            if ($bodyContent && in_array($request->method(), ['POST', 'PATCH'])) {
                foreach ($request->all() as $key => $value) {
                    $strValue = serialize($value);
                    if (Str::length($strValue) > $parameterLength) {
                        // 参数内容的长度过大的进行裁剪
                        $bodyContent[$key] = Str::limit($strValue, $parameterLength);
                    }
                }
            }
            $requestMessage['body'] = $bodyContent;
        }
        Log::channel('request')->info('record request message', $requestMessage);
        return $next($request);
    }
}

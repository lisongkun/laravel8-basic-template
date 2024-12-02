<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
 * 通用控制器
 */
class CommonController extends Controller
{
    public function upload(\Request $request)
    {
        if (!$request->hasFile('file')) {
            Response::fail('没有上传文件', ResponseCodeEnum::CLIENT_PARAMETER_ERROR);
        }
        $fileName = md5(time()) . '.' . $request->file('file')->extension();
        $todayFormat = date('Ymd');
        Storage::disk('public')->putFileAs("upload/{$todayFormat}/", $request->file('file'), $fileName);
        return Response::success([
            'url' => Storage::disk('public')->url("upload/{$todayFormat}/{$fileName}"),
        ]);
    }
}

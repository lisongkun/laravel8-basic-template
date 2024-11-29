<?php

namespace App\Utils;

class ResponseUtil
{
    /**
     * 获取分页响应
     * @param $items
     * @param $total
     * @param $perPage
     * @param array $extra
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public static function paginationResponse($items, $total, $perPage, array $extra = [])
    {
        $result = [
            'items' => $items,
            'total' => $total,
            'perPage' => $perPage,
        ];
        if ($extra) {
            $result['extra'] = $extra;
        }
        return \Response::success($result);
    }
}

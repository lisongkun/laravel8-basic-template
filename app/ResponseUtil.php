<?php

namespace App;

class ResponseUtil
{
    /**
     * 获取分页响应
     * @param $items
     * @param $total
     * @param $perPage
     * @param array $extra
     * @return array
     */
    public static function paginationResponse($items, $total, $perPage, array $extra = []): array
    {
        $result = [
            'items' => $items,
            'total' => $total,
            'perPage' => $perPage,
        ];
        if ($extra) {
            $result['extra'] = $extra;
        }
        return $result;
    }
}

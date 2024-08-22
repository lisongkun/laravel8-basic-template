<?php

namespace App\Exceptions;

use Exception;

/**
 * @className: BusinessException
 * @Description: 业务的自定义异常
 * @CreatedBy: lisongkun
 * @CreatedAt: 2024/8/7 8:46
 */
class BusinessException extends Exception
{
    public function __construct($code = 0, $message = '', Exception $previous = null)
    {
        $message = $message ?? 'Business Exception';
        parent::__construct($message, $code, $previous);
    }
}

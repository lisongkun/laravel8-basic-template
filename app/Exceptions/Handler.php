<?php

namespace App\Exceptions;

use App\Enums\ResponseCodeEnum;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Jiannei\Response\Laravel\Support\Facades\Response;
use Jiannei\Response\Laravel\Support\Traits\ExceptionTrait;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ExceptionTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            $firstErrorMessage = array_values($e->errors())[0][0];
            Response::fail($firstErrorMessage, ResponseCodeEnum::HTTP_BAD_REQUEST);
        }
        if ($e instanceof ModelNotFoundException)
            Response::fail('数据不存在', ResponseCodeEnum::HTTP_NOT_FOUND);
        if (!config('app.debug')) {
            if ($e instanceof UnauthorizedHttpException)
                Response::fail('您的会话已过期,请重新登陆', ResponseCodeEnum::HTTP_UNAUTHORIZED);
            else if ($e instanceof HttpResponseException)
                return parent::render($request, $e);
            elseif ($e instanceof BusinessException)
                Response::fail($e->getMessage(), $e->getCode());
            else {
                Log::error('系统异常', [
                    'error' => $e,
                    'message' => $e->getMessage(),
                    'request' => $request->all(),
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'ip' => $request->getClientIp(),
                    'user_agent' => $request->header('User-Agent'),
                    'headers' => $request->header(),
                    'full_url' => $request->fullUrl(),
                ]);
                Response::fail('系统异常', ResponseCodeEnum::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            Log::error('系统异常', [
                'error' => $e,
                'message' => $e->getMessage(),
                'request' => $request->all(),
                'path' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->getClientIp(),
                'user_agent' => $request->header('User-Agent'),
                'headers' => $request->header(),
                'full_url' => $request->fullUrl(),
            ]);
            return parent::render($request, $e);
        }
    }
}

<?php

namespace App\Exceptions;

use App\Core\ApiErrorHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->reportable(static function (\Throwable $e) {});
    }

    /**
     * @param $request
     * @param \Throwable $e
     *
     * @return JsonResponse
     */
    public function render($request, \Throwable $e): JsonResponse
    {
        return $this->handleApiException($e);
    }

    /**
     * @param \Throwable $exception
     *
     * @return JsonResponse
     */
    private function handleApiException(\Throwable $exception): JsonResponse
    {
        $exception = $this->prepareException($exception);

        return (new ApiErrorHandler())->customApiResponse($exception);
    }
}

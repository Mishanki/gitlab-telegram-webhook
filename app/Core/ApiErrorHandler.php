<?php

namespace App\Core;

use App\Exceptions\SQLException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Client\ConnectionException as HttpClientConnectionException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Monolog\Level;
use Monolog\Logger;
use Predis\Connection\ConnectionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as ComponentNotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiErrorHandler
{
    /**
     * @param \Throwable $e
     *
     * @return JsonResponse
     *
     * @throws \App\Exceptions\ValidationException
     */
    public function customApiResponse(\Throwable $e): JsonResponse
    {
        $data = $this->customApiLog($e);

        return response()->json($data['response'], $data['statusCode']);
    }

    /**
     * @param \Throwable $e
     * @param array $customData
     *
     * @return array
     *
     * @throws \App\Exceptions\ValidationException
     */
    public function customApiLog(\Throwable $e, array $customData = []): array
    {
        if (method_exists($e, 'getStatusCode')) {
            $statusCode = $e->getStatusCode();
        } else {
            $statusCode = Response::HTTP_BAD_REQUEST;
        }

        $level = Logger::ALERT;

        $response['code'] = $e->getCode();
        $response['message'] = $e->getMessage();

        if ($e instanceof \ArgumentCountError) {
            $response['message'] = 'Argument count error';
            $response['code'] = Errors::INTERNAL_ERROR->value;
        } elseif ($e instanceof ComponentNotFoundHttpException && $e->getMessage() == '') {
            $response['message'] = 'Method is not found';
            $response['code'] = Errors::INTERNAL_ERROR->value;
        } elseif ($e instanceof ValidationException) {
            $response['errors'] = $e->errors();
            $statusCode = $e->status;
            $response['code'] = Errors::VALIDATION_ERROR->value;
        } elseif ($e instanceof AccessDeniedHttpException) {
            $response['code'] = Errors::AUTHORIZATION_ERROR->value;
        } elseif ($e instanceof QueryException && str_contains($e->getMessage(), 'Foreign key violation')) {
            $response['message'] = 'Foreign key violation';
            $response['code'] = Errors::CONFLICT_ERROR->value;
            $statusCode = Response::HTTP_CONFLICT;
        } elseif ($e instanceof QueryException && str_contains($e->getMessage(), 'connection to server at')) {
            $response['message'] = Errors::DB_CONNECTION_ERROR->message();
            $response['code'] = Errors::DB_CONNECTION_ERROR->value;
            $statusCode = Response::HTTP_CONFLICT;
        } elseif ($e instanceof UniqueConstraintViolationException) {
            $response['message'] = 'Unique key violation';
            $response['code'] = Errors::SQL_UNIQUE_KEY_ERROR->value;
            $statusCode = Response::HTTP_CONFLICT;
        } elseif ($e instanceof SQLException || $e instanceof \PDOException) {
            $response['code'] = Errors::SQL_ERROR->value;
            $response['message'] = Errors::SQL_ERROR->message();
        } elseif ($e instanceof ConnectionException) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response['code'] = Errors::REDIS_CONNECTION_ERROR->value;
            $response['message'] = Errors::REDIS_CONNECTION_ERROR->message();
        } elseif ($e instanceof HttpClientConnectionException) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response['code'] = Errors::HTTP_CONNECTION_ERROR->value;
            $response['message'] = Errors::HTTP_CONNECTION_ERROR->message();
        } elseif ($e instanceof ModelNotFoundException || $e instanceof ComponentNotFoundHttpException && $e->getMessage() != '') {
            $response['code'] = Errors::NOT_FOUND_ERROR->value;
            $statusCode = Errors::NOT_FOUND_ERROR->httpCode();
            $level = Logger::WARNING;
            $response['message'] = Errors::NOT_FOUND_ERROR->message();
        } elseif ($e instanceof UnauthorizedHttpException || $e instanceof AuthenticationException) {
            $response['code'] = Errors::AUTHORIZATION_ERROR->value;
            $response['message'] = Errors::AUTHORIZATION_ERROR->message();
            $statusCode = Errors::AUTHORIZATION_ERROR->httpCode();
        } elseif ($e instanceof \TypeError) {
            $response['message'] = 'Type error';
            $response['code'] = Errors::INTERNAL_ERROR->value;
        } elseif ($e instanceof \UnexpectedValueException) {
            $response['message'] = 'Unexpected value';
            $response['code'] = Errors::UNEXPECTED_VALUE_EXCEPTION->value;
        } elseif ($e instanceof ThrottleRequestsException) {
            $response['code'] = Errors::RATE_LIMITER_ERROR->value;
        }

        $logMessage = $e->getMessage() ?: $response['message'];

        Log::log(Level::fromValue($level)->name, $logMessage, array_merge([
            'class' => \get_class($e),
            'internal_code' => $response['code'],
            'http_status_code' => $statusCode,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ], $customData));

        return [
            'response' => $response,
            'statusCode' => $statusCode,
        ];
    }
}

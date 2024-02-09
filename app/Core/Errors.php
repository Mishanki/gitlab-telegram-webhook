<?php

namespace App\Core;

use App\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Response;

enum Errors: int
{
    case METHOD_IS_NOT_FOUND = 1000;

    case ARGUMENT_COUNT_ERROR = 1001;

    case INTERNAL_ERROR = 1002;

    case SQL_ERROR = 1003;

    case NOT_FOUND_ERROR = 1004;

    case VALIDATION_ERROR = 1005;

    case AUTHORIZATION_ERROR = 1006;

    case DATABASE_UNIQUE_ERROR = 1007;

    case LOGOUT_AUTH_ERROR = 1008;

    case REDIS_CONNECTION_ERROR = 1009;

    case MODEL_IS_EMPTY = 1010;

    case FORBIDDEN_ERROR = 1011;

    case CONFLICT_ERROR = 1012;

    case DB_CONNECTION_ERROR = 1017;

    case INVALID_AUTH_CREDENTIALS = 1013;

    case AUTHORIZATION_TIMEOUT_ERROR = 1014;

    case GUZZLE_CONNECTION_ERROR = 1015;

    case UNEXPECTED_VALUE_EXCEPTION = 1016;

    case INVALID_MATCH_EXCEPTION = 1018;

    case FILE_LOADER_EXISTS_ERROR = 1019;

    case CREATE_FOLDER_ERROR = 1020;

    case HTTP_CONNECTION_ERROR = 1024;

    case RATE_LIMITER_ERROR = 1025;

    case SQL_UNIQUE_KEY_ERROR = 1026;

    case TELEGRAM_RESPONSE_ERROR = 9001;

    case TELEGRAM_RULE_DUBLICATE_ERROR = 9002;

    case TELEGRAM_REQUEST_EXCEPTION = 9003;

    /**
     * @return string
     *
     * @throws ValidationException
     */
    public function message(): string
    {
        return match ($this) {
            self::AUTHORIZATION_ERROR => 'User not authorized',
            self::NOT_FOUND_ERROR => 'Rows is not found',
            self::REDIS_CONNECTION_ERROR => 'Redis connection error',
            self::SQL_ERROR => 'Sql error',
            self::INVALID_AUTH_CREDENTIALS => 'The provided credentials are incorrect',
            self::FORBIDDEN_ERROR => 'Access is forbidden',
            self::DB_CONNECTION_ERROR => 'Database connection error',
            self::FILE_LOADER_EXISTS_ERROR => 'File has been uploaded before',
            self::HTTP_CONNECTION_ERROR => 'HTTP connection error',
            default => throw new ValidationException('Invalid errors message for value:'.$this->value, self::INVALID_MATCH_EXCEPTION->value),
        };
    }

    /**
     * @return int
     *
     * @throws ValidationException
     */
    public function httpCode(): int
    {
        return match ($this) {
            self::NOT_FOUND_ERROR => Response::HTTP_NOT_FOUND,
            self::AUTHORIZATION_ERROR => Response::HTTP_UNAUTHORIZED,
            self::INVALID_AUTH_CREDENTIALS => Response::HTTP_UNAUTHORIZED,
            default => throw new ValidationException('Invalid errors http code for value:'.$this->value, self::INVALID_MATCH_EXCEPTION->value),
        };
    }
}

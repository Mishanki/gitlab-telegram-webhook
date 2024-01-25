<?php

namespace App\Exceptions\Http;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthenticationTimeoutHTTPException extends HttpException
{
    /**
     * AuthenticationTimeoutHTTPException constructor.
     *
     * @param null|string $message
     * @param int $code
     * @param array $headers
     */
    public function __construct(?string $message = '', int $code = 0, array $headers = [])
    {
        parent::__construct(419, $message, null, $headers, $code);
    }
}

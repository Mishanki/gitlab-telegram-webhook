<?php

namespace App\Exceptions\Http;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ForbiddenHTTPException extends HttpException
{
    /**
     * BadRequestHTTPException constructor.
     *
     * @param null|string $message
     * @param int $code
     * @param array $headers
     */
    public function __construct(?string $message = '', int $code = 0, array $headers = [])
    {
        parent::__construct(403, $message, null, $headers, $code);
    }
}

<?php

namespace App\Logging;

use App\Logging\Processor\HTTPMethodProcessor;
use App\Logging\Processor\ParamsProcessor;
use App\Logging\Processor\RouteActionProcessor;
use App\Logging\Processor\UserIdProcessor;
use Illuminate\Log\Logger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;

class CustomizeFormatter
{
    /**
     * @param Logger $logger
     */
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            /** @var RotatingFileHandler $handler */
            $jsonFormatter = new JsonFormatter();
            $jsonFormatter->includeStacktraces();
            $handler->setFormatter($jsonFormatter);
            $handler->setFilenameFormat('{date}/{filename}', 'Y/m/d');
            $handler->pushProcessor(new RouteActionProcessor());
            $handler->pushProcessor(new UserIdProcessor());
            $handler->pushProcessor(new ParamsProcessor());
            $handler->pushProcessor(new HTTPMethodProcessor());
        }
    }
}

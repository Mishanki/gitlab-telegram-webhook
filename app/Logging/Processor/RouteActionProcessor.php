<?php

declare(strict_types=1);

namespace App\Logging\Processor;

use Illuminate\Support\Facades\Route;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class RouteActionProcessor implements ProcessorInterface
{
    /**
     * @param LogRecord $record
     *
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $record['extra']['route'] = Route::currentRouteAction();
        $record['extra']['url'] = url()->current();
        $record['extra']['full_url'] = url()->full();

        return $record;
    }
}

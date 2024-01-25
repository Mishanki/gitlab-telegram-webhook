<?php

declare(strict_types=1);

namespace App\Logging\Processor;

use Illuminate\Support\Facades\Auth;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class UserIdProcessor implements ProcessorInterface
{
    /**
     * @param LogRecord $record
     *
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $record['extra']['user_id'] = Auth::user()->id ?? null;

        return $record;
    }
}

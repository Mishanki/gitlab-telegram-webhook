<?php

declare(strict_types=1);

namespace App\Logging\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class PostProcessor implements ProcessorInterface
{
    /**
     * @param LogRecord $record
     *
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $record['extra']['post'] = request()->post();

        return $record;
    }
}

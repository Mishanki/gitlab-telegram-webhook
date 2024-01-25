<?php

declare(strict_types=1);

namespace App\Logging\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class ParamsProcessor implements ProcessorInterface
{
    /**
     * @param LogRecord $record
     *
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $params = request()->all();
        if (!empty($params['password'])) {
            $params['password'] = $this->hidePassword($params['password']);
        }
        $record['extra']['params'] = $params;

        return $record;
    }

    /**
     * @param string $password
     *
     * @return string
     */
    private function hidePassword(string $password): string
    {
        return mb_substr($password, 0, 1).str_repeat('*', mb_strlen(mb_substr($password, 1)));
    }
}

<?php

namespace App\Helper;

class StatusHelper
{
    /**
     * @param string $current
     * @param string $next
     *
     * @return bool
     */
    public static function isChange(string $current, string $next): bool
    {
        $statusStack = ['created', 'pending', 'running', 'failed', 'skipped', 'success'];
        $currentKey = array_search($current, $statusStack, true);
        $nextKey = array_search($next, $statusStack, true);

        return $nextKey > $currentKey;
    }
}

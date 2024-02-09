<?php

namespace App\Helper\Log;

class TelegramLogHelper
{
    /**
     * @param null|string $string
     *
     * @return null|string
     */
    public static function hideBotInfo(?string $string = null): ?string
    {
        if (!$string) {
            return null;
        }

        $pattern = '/\/bot[0-9]*:[a-zA-Z0-9-]*\//';
        $replacement = '/bot**:**-**/';

        return preg_replace($pattern, $replacement, $string);
    }
}

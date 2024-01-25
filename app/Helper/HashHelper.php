<?php

namespace App\Helper;

class HashHelper
{
    /**
     * @param string $hash
     *
     * @return null|string
     */
    public static function getChatIdByHash(string $hash): ?string
    {
        $envHash = env('TELEGRAM_HASH_CHAT_IDS');
        $envHashArr = explode(';', $envHash);
        foreach ($envHashArr as $envHashItem) {
            $itemArr = explode(':', $envHashItem);
            $envHash = $itemArr[0] ?? null;
            if ($envHash && $envHash === $hash) {
                $chatId = IntHelper::stringToIntOrNullShort($itemArr[1] ?? null);

                break;
            }
        }

        return $chatId ?? null;
    }
}

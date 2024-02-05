<?php

namespace App\Network\Telegram;

use App\Core\Errors;
use App\Exceptions\ApplicationException;
use Psr\SimpleCache\InvalidArgumentException;

class TelegramHTTPService implements TelegramHTTPServiceInterface
{
    /**
     * @param TelegramHTTPInterface $http
     */
    public function __construct(
        public TelegramHTTPInterface $http,
    ) {}

    /**
     * @param int $chatId
     * @param string $text
     *
     * @return null|array
     */
    public function sendMessage(int $chatId, string $text): ?array
    {
        $response = $this->http->sendMessage($chatId, $text)->json();
        if (!($response['ok'] ?? false)) {
            throw new ApplicationException('Telegram response error', Errors::TELEGRAM_RESPONSE_ERROR->value);
        }

        return $response['result'] ?? null;
    }

    /**
     * @param int $chatId
     * @param int $messageId
     * @param string $text
     *
     * @return null|array
     * @throws InvalidArgumentException
     */
    public function editMessage(int $chatId, int $messageId, string $text): ?array
    {
        if (\Cache::get($chatId.'_'.$messageId) == $text) {
            return null;
        }
        \Cache::set($chatId.'_'.$messageId, $text, 60 * 5);

        $response = $this->http->editMessage($chatId, $messageId, $text)->json();
        if (!($response['ok'] ?? false)) {
            throw new ApplicationException('Telegram response error: '.$response['description'] ?? null, Errors::TELEGRAM_RESPONSE_ERROR->value);
        }

        return $response['result'] ?? null;
    }

    /**
     * @param null|int $chatId
     *
     * @return array
     */
    public function getUpdates(?int $chatId = null): array
    {
        return $this->http->getUpdates($chatId)->json();
    }
}

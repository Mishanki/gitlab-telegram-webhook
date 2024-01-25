<?php

namespace App\Network\Telegram;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TelegramHTTP implements TelegramHTTPInterface
{
    /**
     * @var string
     */
    public string $url;

    /**
     * @var int
     */
    private int $httpTimeout;

    public function __construct()
    {
        $this->httpTimeout = env('TELEGRAM_BOT_TIMEOUT', 10);
        $this->url = env('TELEGRAM_BOT_HOST').env('TELEGRAM_BOT_TOKEN');
    }

    /**
     * @return Response
     */
    public function getUpdates(): Response
    {
        return Http::timeout($this->httpTimeout)
            ->post($this->url.'/getUpdates')
        ;
    }

    /**
     * @param int $chatId
     * @param string $text
     *
     * @return Response
     */
    public function sendMessage(int $chatId, string $text): Response
    {
        return Http::timeout($this->httpTimeout)
            ->post($this->url.'/sendMessage', [
                'disable_web_page_preview' => true,
                'parse_mode' => 'HTML',
                'chat_id' => $chatId,
                'text' => $text,
            ])
        ;
    }

    /**
     * @param int $chatId
     * @param int $messageId
     * @param string $text
     *
     * @return Response
     */
    public function editMessage(int $chatId, int $messageId, string $text): Response
    {
        return Http::timeout($this->httpTimeout)
            ->post($this->url.'/editMessageText', [
                'disable_web_page_preview' => true,
                'parse_mode' => 'HTML',
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
            ])
        ;
    }
}

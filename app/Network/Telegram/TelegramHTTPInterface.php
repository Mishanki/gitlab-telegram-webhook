<?php

namespace App\Network\Telegram;

use Illuminate\Http\Client\Response;

interface TelegramHTTPInterface
{
    public function getUpdates(): Response;

    public function sendMessage(int $chatId, string $text): Response;

    public function editMessage(int $chatId, int $messageId, string $text): Response;
}

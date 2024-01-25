<?php

namespace App\Network\Telegram;

interface TelegramHTTPServiceInterface
{
    public function sendMessage(int $chatId, string $text): ?array;

    public function editMessage(int $chatId, int $messageId, string $text): ?array;

    public function getUpdates(): array;
}

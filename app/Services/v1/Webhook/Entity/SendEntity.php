<?php

namespace App\Services\v1\Webhook\Entity;

class SendEntity
{
    /**
     * @var null|string
     */
    public ?string $hash = null;

    /**
     * @var int
     */
    public int $chatId;

    /**
     * @var string
     */
    public string $hook;

    /**
     * @var array
     */
    public array $body;

    /**
     * @return null|string
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @param null|string $hash
     */
    public function setHash(?string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return int
     */
    public function getChatId(): int
    {
        return $this->chatId;
    }

    /**
     * @param int $chatId
     */
    public function setChatId(int $chatId): void
    {
        $this->chatId = $chatId;
    }

    /**
     * @return string
     */
    public function getHook(): string
    {
        return $this->hook;
    }

    /**
     * @param string $hook
     */
    public function setHook(string $hook): void
    {
        $this->hook = $hook;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @param array $body
     */
    public function setBody(array $body): void
    {
        $this->body = $body;
    }
}

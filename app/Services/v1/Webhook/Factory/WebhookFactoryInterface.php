<?php

namespace App\Services\v1\Webhook\Factory;

use App\Services\v1\Webhook\Entity\SendEntity;

interface WebhookFactoryInterface
{
    /**
     * @param SendEntity $entity
     *
     * @return null|array
     */
    public function send(SendEntity $entity): ?array;

    /**
     * @param array $body
     *
     * @return array
     */
    public function getData(array $body): array;

    /**
     * @param array $data
     * @param null|string $render
     *
     * @return string
     */
    public function getTemplate(array $data, ?string $render = null): string;

    /**
     * @param array $body
     *
     * @return string
     */
    public function getHash(array $body): string;
}

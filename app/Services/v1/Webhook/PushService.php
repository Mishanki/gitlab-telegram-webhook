<?php

namespace App\Services\v1\Webhook;

use App\Network\Telegram\TelegramHTTPServiceInterface;
use App\Repositories\HookRepositoryInterface;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\Factory\WebhookFactoryInterface;

class PushService implements WebhookFactoryInterface
{
    /**
     * @param TelegramHTTPServiceInterface $http
     * @param HookRepositoryInterface $hookRepository
     */
    public function __construct(
        public TelegramHTTPServiceInterface $http,
        public HookRepositoryInterface $hookRepository,
    ) {}

    /**
     * @param SendEntity $entity
     *
     * @return array
     */
    public function send(SendEntity $entity): array
    {
        $data = $this->getData($entity->getBody());
        $shaHash = $this->getHash($entity->getBody());
        $tpl = $this->getTemplate($data);

        $response = $this->http->sendMessage($entity->getChatId(), $tpl);

        $this->hookRepository->store([
            'event' => $entity->getHook(),
            'hash' => $shaHash,
            'body' => $entity->getBody(),
            'short_body' => null,
            'render' => $tpl,
            'message_id' => $response['message_id'],
        ]);

        return $response;
    }

    /**
     * @param array $body
     *
     * @return string
     */
    public function getHash(array $body): string
    {
        return $body['after'];
    }

    /**
     * @param array $body
     *
     * @return array
     */
    public function getData(array $body): array
    {
        return $body;
    }

    /**
     * @param array $data
     * @param null|string $render
     *
     * @return string
     */
    public function getTemplate(array $data, ?string $render = null): string
    {
        return view('push.default', $data)->render();
    }
}

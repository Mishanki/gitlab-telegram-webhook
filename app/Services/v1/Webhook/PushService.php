<?php

namespace App\Services\v1\Webhook;

use App\Network\Telegram\TelegramHTTPServiceInterface;
use App\Repositories\HookRepositoryInterface;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\Factory\WebhookFactoryInterface;
use App\Services\v1\Webhook\Rule\Push\PushPipeRule;
use App\Services\v1\Webhook\Rule\Push\PushRule;
use App\Services\v1\Webhook\Trait\RuleTrait;

class PushService implements WebhookFactoryInterface
{
    use RuleTrait;

    /**
     * @param TelegramHTTPServiceInterface $http
     * @param HookRepositoryInterface $hookRepository
     * @param PipelineService $pipelineService
     */
    public function __construct(
        public TelegramHTTPServiceInterface $http,
        public HookRepositoryInterface $hookRepository,
        public PipelineService $pipelineService,
    ) {}

    /**
     * @param SendEntity $entity
     *
     * @return null|array
     */
    public function send(SendEntity $entity): ?array
    {
        $data = $this->getData($entity->getBody());
        $shaHash = $this->getHash($entity->getBody());
        $tpl = $this->getTemplate($data);

        $response = $this->ruleWork([
            PushRule::class,
            PushPipeRule::class,
        ], $entity);

        if ($response) {
            $this->hookRepository->store([
                'event' => $entity->getHook(),
                'hash' => $shaHash,
                'body' => $entity->getBody(),
                'short_body' => null,
                'render' => $tpl,
                'message_id' => $response['message_id'],
            ]);
        }

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
        $tpl = view('push.default', $data)->render();
        if ($render) {
            $tpl = $tpl. PHP_EOL . $render.PHP_EOL;
        }

        return $tpl;
    }
}

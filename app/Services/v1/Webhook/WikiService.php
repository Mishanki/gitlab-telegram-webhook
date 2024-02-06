<?php

namespace App\Services\v1\Webhook;

use App\Network\Telegram\TelegramHTTPServiceInterface;
use App\Repositories\HookRepositoryInterface;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\Factory\WebhookFactoryInterface;
use App\Services\v1\Webhook\Rule\MergeRequest\MergeRequestOpenRule;
use App\Services\v1\Webhook\Rule\WikiPage\WikiPageRule;
use App\Services\v1\Webhook\Trait\RuleTrait;

class WikiService implements WebhookFactoryInterface
{
    use RuleTrait;

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
     * @return bool
     */
    public function send(SendEntity $entity): bool
    {
        $data = $this->getData($entity->getBody());
        $shaHash = $this->getHash($entity->getBody());
        $tpl = $this->getTemplate($data);

        $response = $this->ruleWork([
            WikiPageRule::class,
        ], $entity);

        if ($response) {
            $this->hookRepository->store([
                'event' => $entity->getHook(),
                'event_id' => $data['item']['build_id'] ?? null,
                'hash' => $shaHash,
                'body' => $entity->getBody(),
                'short_body' => $data,
                'render' => $tpl,
                'message_id' => $response['message_id'] ?? null,
            ]);
        }

        return true;
    }

    /**
     * @param array $body
     *
     * @return string
     */
    public function getHash(array $body): string
    {
        return '';
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
        $tpl = view('wiki_page.'.$this->getStatus($data), $data)->render();
        if ($render) {
            $tpl = $render.PHP_EOL.$tpl;
        }

        return $tpl;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function getStatus(array $data): string
    {
        return $data['object_attributes']['action'] ?? 'default';
    }
}

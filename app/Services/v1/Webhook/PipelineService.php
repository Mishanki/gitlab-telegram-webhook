<?php

namespace App\Services\v1\Webhook;

use App\Helper\IconHelper;
use App\Models\Hook\Enum\HookEnum;
use App\Network\Telegram\TelegramHTTPServiceInterface;
use App\Repositories\HookRepositoryInterface;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\Factory\WebhookFactoryInterface;

class PipelineService implements WebhookFactoryInterface
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
        $sendTpl = $this->getTemplate($data);
        $shaHash = $this->getHash($entity->getBody());
        if (!$push = $this->hookRepository->findOneByEventSha(HookEnum::HOOK_PUSH->value, $shaHash)) {
            $response = $this->http->sendMessage($entity->getChatId(), $sendTpl);
        } else {
            $editTpl = $this->getTemplate($data, $push->render);
            //            echo '<pre>';
            //            print_r($editTpl); die();
            //            echo '</pre>';
            $response = $this->http->editMessage($entity->getChatId(), $push->message_id, $editTpl);
        }

        $this->hookRepository->store([
            'event' => $entity->getHook(),
            'hash' => $shaHash,
            'body' => $entity->getBody(),
            'short_body' => $data,
            'render' => $sendTpl,
            'message_id' => $response['message_id'] ?? null,
        ]);

        return $response;
    }

    /**
     * @param array $body
     *
     * @return array
     */
    public function getData(array $body): array
    {
        $stages = $body['object_attributes']['stages'] ?? [];
        foreach ($body['builds'] ?? [] as $build) {
            switch ($build['status']) {
                case 'created':
                case 'pending':
                case 'running':
                case 'failed':
                case 'success':
                    $message[$build['id']] = [
                        'icon' => IconHelper::ICONS[$build['status']] ?? null,
                        'url' => $body['project']['web_url'].'/builds/'.$build['id'],
                        'name' => $build['name'],
                        'status' => $build['status'],
                        'stage' => $build['stage'],
                        'duration' => $build['duration'],
                        'queued_duration' => $build['queued_duration'],
                        'sort_num' => array_search($build['stage'], $stages, true),
                    ];

                    break;
            }
        }
        if (!empty($message)) {
            $message = collect($message)->sortBy([['sort_num', 'asc'], ['stage', 'asc']])->toArray();
        }

        return [
            'message' => $message ?? [],
        ];
    }

    /**
     * @param array $data
     * @param null|string $render
     *
     * @return string
     */
    public function getTemplate(array $data, ?string $render = null): string
    {
        $tpl = view('pipeline.default', $data)->render();
        if ($render) {
            $tpl = $render.PHP_EOL.$tpl;
        }

        return $tpl;
    }

    /**
     * @param array $body
     *
     * @return string
     */
    public function getHash(array $body): string
    {
        return $body['object_attributes']['sha'];
    }

    /**
     * @param array $data
     * @param array $update
     * @param array $updKeys
     *
     * @return array
     */
    public function updateData(array $data, array $update, array $updKeys = []): array
    {
        if (!$update) {
            return $data;
        }

        $id = $update['build_id'];
        foreach ($data['message'][$id] as $k => $item) {
            if (\in_array($k, $updKeys, true)) {
                $data['message'][$id][$k] = $update[$k];
            }
        }

        return $data;
    }
}

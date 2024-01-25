<?php

namespace App\Services\v1\Webhook;

use App\Helper\IconHelper;
use App\Models\Hook\Enum\HookEnum;
use App\Network\Telegram\TelegramHTTPServiceInterface;
use App\Repositories\HookRepositoryInterface;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\Factory\WebhookFactoryInterface;

class JobService implements WebhookFactoryInterface
{
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
     * @return array
     */
    public function send(SendEntity $entity): array
    {
        $data = $this->getData($entity->getBody());
        $shaHash = $this->getHash($entity->getBody());
        $tpl = $this->getTemplate($data);

        $push = $this->hookRepository->findOneByEventSha(HookEnum::HOOK_PUSH->value, $shaHash);
        $pipe = $this->hookRepository->findOneByEventSha(HookEnum::HOOK_PIPELINE->value, $shaHash);
        $job = $this->hookRepository->findAllByEventSha(HookEnum::HOOK_JOB->value, $shaHash);

        $response = [];
        if (!$push && !$pipe) {
            $response = $this->http->sendMessage($entity->getChatId(), $tpl);
        }
        if ($push && !$pipe) {
            $editTpl = $this->getTemplate($data, $push->render);
            $response = $this->http->editMessage($entity->getChatId(), $push->message_id, $editTpl);
        }
        if ($push && $pipe) {
            foreach ($job ?? [] as $jobItem) {
                $pipe->short_body = $this->pipelineService->updateData((array) ($pipe->short_body ?? []), $jobItem->short_body ?? [], ['icon', 'status', 'duration', 'queued_duration']);
            }

            $shortBody = $this->pipelineService->updateData($pipe->short_body ?? [], $data, ['icon', 'status', 'duration', 'queued_duration']);
            $editTpl = $this->pipelineService->getTemplate($shortBody, $push->render);
            $response = $this->http->editMessage($entity->getChatId(), $push->message_id, $editTpl);
        }

        $this->hookRepository->store([
            'event' => $entity->getHook(),
            'hash' => $shaHash,
            'body' => $entity->getBody(),
            'short_body' => $data,
            'render' => $tpl,
            'message_id' => $response['message_id'] ?? null,
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
        return $body['sha'];
    }

    /**
     * @param array $body
     *
     * @return array
     */
    public function getData(array $body): array
    {
        return [
            'build_id' => $body['build_id'],
            'icon' => IconHelper::ICONS[$body['build_status']] ?? null,
            'name' => $body['build_name'],
            'status' => $body['build_status'],
            'duration' => $body['build_duration'],
            'queued_duration' => $body['build_queued_duration'],
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
        $tpl = view('job.default', $data)->render();
        if ($render) {
            $tpl = $render.PHP_EOL.$tpl;
        }

        return $tpl;
    }
}

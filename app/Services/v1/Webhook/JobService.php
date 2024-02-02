<?php

namespace App\Services\v1\Webhook;

use App\Helper\IconHelper;
use App\Network\Telegram\TelegramHTTPServiceInterface;
use App\Repositories\HookRepositoryInterface;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\Factory\WebhookFactoryInterface;
use App\Services\v1\Webhook\Rule\Job\JobPipeRule;
use App\Services\v1\Webhook\Rule\Job\JobPushPipeRule;
use App\Services\v1\Webhook\Rule\Job\JobPushRule;
use App\Services\v1\Webhook\Rule\Job\JobRule;
use Illuminate\Contracts\Container\BindingResolutionException;

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
     *
     * @throws BindingResolutionException
     */
    public function send(SendEntity $entity): array
    {
        $data = $this->getData($entity->getBody());
        $shaHash = $this->getHash($entity->getBody());
        $tpl = $this->getTemplate($data);

        $response = JobRule::rule($entity, null);
        $response = JobPushRule::rule($entity, $response);
        $response = JobPipeRule::rule($entity, $response);
        $response = JobPushPipeRule::rule($entity, $response);

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

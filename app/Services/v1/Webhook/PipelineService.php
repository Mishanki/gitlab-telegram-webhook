<?php

namespace App\Services\v1\Webhook;

use App\Helper\IconHelper;
use App\Helper\StatusHelper;
use App\Models\Hook\HookModel;
use App\Network\Telegram\TelegramHTTPServiceInterface;
use App\Repositories\HookRepositoryInterface;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\Factory\WebhookFactoryInterface;
use App\Services\v1\Webhook\Rule\Pipeline\PipelineJobRule;
use App\Services\v1\Webhook\Rule\Pipeline\PipelinePushRule;
use App\Services\v1\Webhook\Rule\Pipeline\PipelineRule;
use App\Services\v1\Webhook\Trait\RuleTrait;
use Illuminate\Support\Collection;

class PipelineService implements WebhookFactoryInterface
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
        $sendTpl = $this->getTemplate($data);
        $shaHash = $this->getHash($entity->getBody());

        $response = $this->ruleWork([
            //            PipelineRule::class,
            PipelineJobRule::class,
            PipelinePushRule::class,
        ], $entity);

        if ($response) {
            $this->hookRepository->store([
                'event' => $entity->getHook(),
                'hash' => $shaHash,
                'body' => $entity->getBody(),
                'short_body' => $data,
                'render' => $sendTpl,
                'message_id' => $response['message_id'] ?? null,
            ]);
        }

        return true;
    }

    /**
     * @param array $body
     *
     * @return array
     */
    public function getData(array $body): array
    {
        $stages = $body['object_attributes']['stages'] ?? [];
        $totalDuration = $body['object_attributes']['duration'] ?? null;
        foreach ($body['builds'] ?? [] as $build) {
            $message[$build['id']] = [
                'icon' => IconHelper::ICONS[$build['status']] ?? null,
                'url' => $body['project']['web_url'].'/builds/'.$build['id'],
                'name' => $build['name'],
                'status' => $build['status'],
                'stage' => $build['stage'],
                'duration' => $build['duration'],
                'queued_duration' => $build['queued_duration'],
                'total_duration' => $totalDuration,
                'sort_num' => array_search($build['stage'], $stages, true),
            ];
        }

        return [
            'message' => $this->sortData($message ?? []),
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
     * @param array $pipeArr
     * @param Collection $jobCollection
     * @param array $updKeys
     *
     * @return array
     */
    public function updateDataByJobCollection(array $pipeArr, Collection $jobCollection, array $updKeys = []): array
    {
        /* @var $jobItem HookModel */
        foreach ($jobCollection as $jobItem) {
            $pipeArr = $this->updateData($pipeArr, $jobItem->short_body ?? [], $updKeys);
        }

        return $pipeArr;
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

        $id = $update['item']['build_id'];
        $nextStatus = $update['item']['status'];
        $reset = false;
        if (!$currentStatus = $data['message'][$id]['status'] ?? null) {
            $data = $this->updateBuildId($id, $data, $update);
            $currentStatus = $data['message'][$id]['status'] ?? null;
            $reset = true;
        }
        foreach ($data['message'][$id] ?? [] as $k => $item) {
            if (!StatusHelper::isChange($currentStatus, $nextStatus) && !$reset) {
                continue;
            }
            if (\in_array($k, $updKeys, true)) {
                $data['message'][$id][$k] = $update['item'][$k];
            }
        }

        $data['message'] = $this->sortData($data['message']);

        return $data;
    }

    /**
     * @param int $id
     * @param array $data
     * @param array $update
     *
     * @return array
     */
    private function updateBuildId(int $id, array $data, array $update): array
    {
        $dataCurrent = collect($data['message'])->where('name', '=', $update['item']['name']);
        $oldId = $dataCurrent->keys()[0] ?? null;
        $data['message'][$id] = $data['message'][$oldId];
        unset($data['message'][$oldId]);
        $data['message'] = $this->sortData($data['message']);

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function sortData(array $data = []): array
    {
        return collect($data)->sortBy([['sort_num', 'asc'], ['name', 'asc']])->toArray();
    }
}

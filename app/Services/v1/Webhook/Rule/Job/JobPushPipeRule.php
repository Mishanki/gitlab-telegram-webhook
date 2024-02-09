<?php

namespace App\Services\v1\Webhook\Rule\Job;

use App\Models\Hook\Enum\HookEnum;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\JobService;
use Illuminate\Contracts\Container\BindingResolutionException;

class JobPushPipeRule
{
    /**
     * @param SendEntity $entity
     * @param null|array $response
     *
     * @return null|array
     *
     * @throws BindingResolutionException
     */
    public static function rule(SendEntity $entity, ?array $response = null): ?array
    {
        /* @var $service JobService */
        $service = app()->make(JobService::class);
        $data = $service->getData($entity->getBody());
        $shaHash = $service->getHash($entity->getBody());

        $push = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_PUSH->value, $shaHash);
        $pipe = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_PIPELINE->value, $shaHash, $push->message_id ?? null);
        $jobCollection = $service->hookRepository->findAllByEventSha(HookEnum::HOOK_JOB->value, $shaHash, $push->message_id ?? null);

        if ($push && $pipe) {
            $pipeArr = (array) ($pipe->short_body ?? []);
            $pipeShortBody = $service->pipelineService->updateDataByJobCollection($pipeArr, $jobCollection, ['icon', 'status', 'duration', 'queued_duration', 'created_at', 'started_at', 'finished_at']);
            $pipeShortBody = $service->pipelineService->updateData($pipeShortBody, $data, ['icon', 'status', 'duration', 'queued_duration', 'created_at', 'started_at', 'finished_at']);
            $editTpl = $service->pipelineService->getTemplate($pipeShortBody, $push->render);

            $response = $service->http->editMessage($entity->getChatId(), $pipe->message_id, $editTpl);
        }

        return $response ?? null;
    }
}

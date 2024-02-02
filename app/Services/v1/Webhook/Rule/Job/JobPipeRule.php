<?php

namespace App\Services\v1\Webhook\Rule\Job;

use App\Models\Hook\Enum\HookEnum;
use App\Models\Hook\HookModel;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\JobService;
use Illuminate\Contracts\Container\BindingResolutionException;

class JobPipeRule
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
        $pipe = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_PIPELINE->value, $shaHash);
        $jobCollection = $service->hookRepository->findAllByEventSha(HookEnum::HOOK_JOB->value, $shaHash);

        if (!$push && $pipe) {
            /* @var $jobItem HookModel */
            foreach ($jobCollection as $jobItem) {
                $pipeShortBody = $service->pipelineService->updateData($pipe->short_body ?? [], $jobItem->short_body ?? [], ['icon', 'status', 'duration', 'queued_duration']);
            }

            $pipeShortBody ??= $pipe->short_body ?? [];
            $shortBody = $service->pipelineService->updateData($pipeShortBody, $data, ['icon', 'status', 'duration', 'queued_duration']);
            $editTpl = $service->pipelineService->getTemplate($shortBody);
            $response = $service->http->editMessage($entity->getChatId(), $pipe->message_id, $editTpl);
        }

        return $response ?? null;
    }
}

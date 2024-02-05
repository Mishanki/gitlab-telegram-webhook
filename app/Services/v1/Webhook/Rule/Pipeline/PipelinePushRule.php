<?php

namespace App\Services\v1\Webhook\Rule\Pipeline;

use App\Models\Hook\Enum\HookEnum;
use App\Models\Hook\HookModel;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\PipelineService;
use Illuminate\Contracts\Container\BindingResolutionException;

class PipelinePushRule
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
        /* @var $service PipelineService */
        $service = app()->make(PipelineService::class);
        $shaHash = $service->getHash($entity->getBody());
        $data = $service->getData($entity->getBody());

        $job = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_JOB->value, $shaHash);
        $push = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_PUSH->value, $shaHash);
        $pipe = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_PIPELINE->value, $shaHash);
        $jobCollection = $service->hookRepository->findAllByEventSha(HookEnum::HOOK_JOB->value, $shaHash, $job->message_id ?? null);

        if ($push) {
            $data = $service->updateDataByJobCollection($data, $jobCollection, ['icon', 'status', 'duration', 'queued_duration']);
            $editTpl = $service->getTemplate($data, $push->render);

            $response = $service->http->editMessage($entity->getChatId(), $push->message_id, $editTpl);
        }

        return $response ?? null;
    }
}

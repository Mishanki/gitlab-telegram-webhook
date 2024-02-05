<?php

namespace App\Services\v1\Webhook\Rule\Pipeline;

use App\Models\Hook\Enum\HookEnum;
use App\Models\Hook\HookModel;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\PipelineService;
use Illuminate\Contracts\Container\BindingResolutionException;

class PipelineJobRule
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


        if (!$push && $job && !$pipe) {
            /* @var $jobItem HookModel */
            foreach ($jobCollection as $jobItem) {
                $editTpl = $service->updateData($data, $jobItem->short_body ?? [], ['icon', 'status', 'duration', 'queued_duration']);
            }
            $editTpl = $service->getTemplate($editTpl);

            $response = $service->http->editMessage($entity->getChatId(), $job->message_id, $editTpl);
        }

        return $response ?? null;
    }
}

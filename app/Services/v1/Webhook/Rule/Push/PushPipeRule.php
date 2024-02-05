<?php

namespace App\Services\v1\Webhook\Rule\Push;

use App\Models\Hook\Enum\HookEnum;
use App\Models\Hook\HookModel;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\PushService;
use Illuminate\Contracts\Container\BindingResolutionException;

class PushPipeRule
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
        /* @var $service PushService */
        $service = app()->make(PushService::class);
        $data = $service->getData($entity->getBody());
        $shaHash = $service->getHash($entity->getBody());

        $pipe = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_PIPELINE->value, $shaHash);
        $jobCollection = $service->hookRepository->findAllByEventSha(HookEnum::HOOK_JOB->value, $shaHash, $push->message_id ?? null);

        if ($pipe) {
            /* @var $jobItem HookModel */
            foreach ($jobCollection as $jobItem) {
                $pipeShortBody = $service->pipelineService->updateData($pipe->short_body ?? [], $jobItem->short_body ?? [], ['icon', 'status', 'duration', 'queued_duration']);
            }

            $pipeShortBody ??= $pipe->short_body ?? [];
            $pipeTpl = $service->pipelineService->getTemplate($pipeShortBody);

            $editTpl = $service->getTemplate($data, $pipeTpl);
            $response = $service->http->editMessage($entity->getChatId(), $pipe->message_id, $editTpl);
        }

        return $response ?? null;
    }
}

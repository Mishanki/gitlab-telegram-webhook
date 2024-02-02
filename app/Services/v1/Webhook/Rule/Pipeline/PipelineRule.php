<?php

namespace App\Services\v1\Webhook\Rule\Pipeline;

use App\Models\Hook\Enum\HookEnum;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\PipelineService;
use Illuminate\Contracts\Container\BindingResolutionException;

class PipelineRule
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
        if ($response) {
            return $response;
        }

        /* @var $service PipelineService */
        $service = app()->make(PipelineService::class);
        $shaHash = $service->getHash($entity->getBody());
        $data = $service->getData($entity->getBody());

        if ($push = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_PUSH->value, $shaHash)) {
            $editTpl = $service->getTemplate($data, $push->render);
            $response = $service->http->editMessage($entity->getChatId(), $push->message_id, $editTpl);
        }

        return $response ?? null;
    }
}

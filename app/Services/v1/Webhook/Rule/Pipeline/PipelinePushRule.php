<?php

namespace App\Services\v1\Webhook\Rule\Pipeline;

use App\Models\Hook\Enum\HookEnum;
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
        if ($response) {
            return $response;
        }

        /* @var $service PipelineService */
        $service = app()->make(PipelineService::class);
        $shaHash = $service->getHash($entity->getBody());
        $data = $service->getData($entity->getBody());

        if (!$service->hookRepository->findOneByEventSha(HookEnum::HOOK_PUSH->value, $shaHash)) {
            $sendTpl = $service->getTemplate($data);
            $response = $service->http->sendMessage($entity->getChatId(), $sendTpl);
        }

        return $response ?? null;
    }
}

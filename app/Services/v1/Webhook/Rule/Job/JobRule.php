<?php

namespace App\Services\v1\Webhook\Rule\Job;

use App\Models\Hook\Enum\HookEnum;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\JobService;
use Illuminate\Contracts\Container\BindingResolutionException;

class JobRule
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

        if (!$push && !$pipe) {
            $sendTpl = $service->getTemplate($data);
            $response = $service->http->sendMessage($entity->getChatId(), $sendTpl);
        }

        return $response ?? null;
    }
}

<?php

namespace App\Services\v1\Webhook\Rule\Push;

use App\Models\Hook\Enum\HookEnum;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\PushService;
use Illuminate\Contracts\Container\BindingResolutionException;

class PushRule
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

        $job = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_JOB->value, $shaHash);
        $push = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_PUSH->value, $shaHash);
        $pipe = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_PIPELINE->value, $shaHash);

        if (!$pipe && !$job && !$push) {
            $sendTpl = $service->getTemplate($data);
            $response = $service->http->sendMessage($entity->getChatId(), $sendTpl);
        }
        if (!$pipe && !$job && $push) {
            $editTpl = $service->getTemplate($data);
            $response = $service->http->editMessage($entity->getChatId(),$push->message_id, $editTpl);
        }

        return $response ?? null;
    }
}

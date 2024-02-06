<?php

namespace App\Services\v1\Webhook\Rule\TagPush;

use App\Models\Hook\Enum\HookEnum;
use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\TagPushService;
use Illuminate\Contracts\Container\BindingResolutionException;

class TagPushRule
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
        /* @var $service TagPushService */
        $service = app()->make(TagPushService::class);
        $data = $service->getData($entity->getBody());
        $shaHash = $service->getHash($entity->getBody());

        $tagPush = $service->hookRepository->findOneByEventSha(HookEnum::HOOK_TAG_PUSH->value, $shaHash);

        if (!$tagPush) {
            $sendTpl = $service->getTemplate($data);
            $response = $service->http->sendMessage($entity->getChatId(), $sendTpl);
        }
        if ($tagPush) {
            $editTpl = $service->getTemplate($data);
            $response = $service->http->editMessage($entity->getChatId(), $tagPush->message_id, $editTpl);
        }

        return $response ?? null;
    }
}

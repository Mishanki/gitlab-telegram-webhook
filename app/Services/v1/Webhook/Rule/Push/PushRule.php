<?php

namespace App\Services\v1\Webhook\Rule\Push;

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
        if ($response) {
            return $response;
        }

        /* @var $service PushService */
        $service = app()->make(PushService::class);
        $data = $service->getData($entity->getBody());
        $sendTpl = $service->getTemplate($data);

        $response = $service->http->sendMessage($entity->getChatId(), $sendTpl);

        return $response ?? null;
    }
}

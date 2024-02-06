<?php

namespace App\Services\v1\Webhook\Rule\Realese;

use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\ReleaseService;
use Illuminate\Contracts\Container\BindingResolutionException;

class ReleaseRule
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
        /* @var $service ReleaseService */
        $service = app()->make(ReleaseService::class);
        $data = $service->getData($entity->getBody());
        $shaHash = $service->getHash($entity->getBody());

        $sendTpl = $service->getTemplate($data);

        $response = $service->http->sendMessage($entity->getChatId(), $sendTpl);

        return $response ?? null;
    }
}

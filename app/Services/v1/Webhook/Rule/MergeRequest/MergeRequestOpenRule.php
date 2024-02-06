<?php

namespace App\Services\v1\Webhook\Rule\MergeRequest;

use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\MergeRequestService;
use Illuminate\Contracts\Container\BindingResolutionException;

class MergeRequestOpenRule
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
        /* @var $service MergeRequestService */
        $service = app()->make(MergeRequestService::class);
        $data = $service->getData($entity->getBody());
        $sendTpl = $service->getTemplate($data);

        return $service->http->sendMessage($entity->getChatId(), $sendTpl) ?? null;
    }
}

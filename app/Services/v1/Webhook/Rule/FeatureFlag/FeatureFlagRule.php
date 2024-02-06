<?php

namespace App\Services\v1\Webhook\Rule\FeatureFlag;

use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\FeatureFlagService;
use Illuminate\Contracts\Container\BindingResolutionException;

class FeatureFlagRule
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
        /* @var $service FeatureFlagService */
        $service = app()->make(FeatureFlagService::class);
        $data = $service->getData($entity->getBody());
        $sendTpl = $service->getTemplate($data);

        return $service->http->sendMessage($entity->getChatId(), $sendTpl) ?? null;
    }
}

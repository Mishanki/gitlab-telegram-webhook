<?php

namespace App\Services\v1\Webhook\Rule\WikiPage;

use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\WikiService;
use Illuminate\Contracts\Container\BindingResolutionException;

class WikiPageRule
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
        /* @var $service WikiService */
        $service = app()->make(WikiService::class);
        $data = $service->getData($entity->getBody());
        $sendTpl = $service->getTemplate($data);

        return $service->http->sendMessage($entity->getChatId(), $sendTpl) ?? null;
    }
}

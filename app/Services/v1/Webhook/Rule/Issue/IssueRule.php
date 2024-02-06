<?php

namespace App\Services\v1\Webhook\Rule\Issue;

use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\IssueService;
use Illuminate\Contracts\Container\BindingResolutionException;

class IssueRule
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
        /* @var $service IssueService */
        $service = app()->make(IssueService::class);
        $data = $service->getData($entity->getBody());
        $sendTpl = $service->getTemplate($data);

        $response = $service->http->sendMessage($entity->getChatId(), $sendTpl);

        return $response ?? null;
    }
}

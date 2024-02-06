<?php

namespace App\Services\v1\Webhook\Rule\Note;

use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\NoteService;
use Illuminate\Contracts\Container\BindingResolutionException;

class NoteRule
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
        /* @var $service NoteService */
        $service = app()->make(NoteService::class);
        $data = $service->getData($entity->getBody());
        $sendTpl = $service->getTemplate($data);

        return $service->http->sendMessage($entity->getChatId(), $sendTpl) ?? null;
    }
}

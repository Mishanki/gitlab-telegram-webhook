<?php

namespace App\Http\Controllers\v1\Action\Webhook;

use App\Http\Requests\v1\Webhook\SendRequest;
use App\Services\v1\Webhook\Entity\SendEntity;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;

class SendAction extends BaseController
{
    /**
     * @param SendRequest $request
     *
     * @return JsonResponse
     *
     * @throws BindingResolutionException
     */
    public function __invoke(SendRequest $request): JsonResponse
    {
        $entity = new SendEntity();
        $entity->setHook($request->validated('hook'));
        $entity->setChatId($request->validated('chat_id'));
        $entity->setHash($request->validated('hash'));
        $entity->setBody($request->validated('body'));

        return response()
            ->json([
                'data' => $this->webhookFactory->create($entity->hook)->send($entity),
            ])
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE)
        ;
    }
}

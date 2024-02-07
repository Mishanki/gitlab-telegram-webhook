<?php

namespace App\Http\Controllers\v1\Action\Webhook;

use App\Http\Requests\v1\Webhook\SendRequest;
use App\Jobs\ProcessWebhook;
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

        ProcessWebhook::dispatch(
            $entity,
            $this->webhookFactory,
            3,
        );

        return response()
            ->json([
                'data' => true,
            ])
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE)
        ;
    }
}

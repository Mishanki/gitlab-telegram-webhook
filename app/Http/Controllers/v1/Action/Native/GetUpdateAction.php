<?php

namespace App\Http\Controllers\v1\Action\Native;

use Illuminate\Http\JsonResponse;

class GetUpdateAction extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => $this->webhookService->getUpdates(),
        ])
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE)
        ;
    }
}

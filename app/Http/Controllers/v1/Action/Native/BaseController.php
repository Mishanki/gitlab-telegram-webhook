<?php

namespace App\Http\Controllers\v1\Action\Native;

use App\Http\Controllers\Controller;
use App\Services\v1\Webhook\WebhookService;

class BaseController extends Controller
{
    /**
     * @param WebhookService $webhookService
     */
    public function __construct(
        public WebhookService $webhookService,
    ) {}
}

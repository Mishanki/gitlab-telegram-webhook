<?php

namespace App\Http\Controllers\v1\Action\Webhook;

use App\Http\Controllers\Controller;
use App\Services\v1\Webhook\Factory\WebhookFactory;

class BaseController extends Controller
{
    /**
     * @param WebhookFactory $webhookFactory
     */
    public function __construct(
        public WebhookFactory $webhookFactory,
    ) {}
}

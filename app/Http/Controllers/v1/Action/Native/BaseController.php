<?php

namespace App\Http\Controllers\v1\Action\Native;

use App\Http\Controllers\Controller;
use App\Network\Telegram\TelegramHTTPServiceInterface;

class BaseController extends Controller
{
    /**
     * @param TelegramHTTPServiceInterface $http
     */
    public function __construct(
        public TelegramHTTPServiceInterface $http,
    ) {}
}

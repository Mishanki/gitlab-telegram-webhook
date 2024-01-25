<?php

namespace App\Providers;

use App\Network\Telegram\TelegramHTTP;
use App\Network\Telegram\TelegramHTTPInterface;
use App\Network\Telegram\TelegramHTTPService;
use App\Network\Telegram\TelegramHTTPServiceInterface;
use App\Repositories\HookRepository;
use App\Repositories\HookRepositoryInterface;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        # Telegram netwrok
        $this->app->bind(TelegramHTTPServiceInterface::class, TelegramHTTPService::class);
        $this->app->bind(TelegramHTTPInterface::class, TelegramHTTP::class);

        # Hook model
        $this->app->bind(HookRepositoryInterface::class, HookRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}

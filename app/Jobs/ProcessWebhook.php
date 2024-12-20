<?php

namespace App\Jobs;

use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\Factory\WebhookFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\RateLimitedMiddleware\RateLimited;

class ProcessWebhook implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param SendEntity $entity
     * @param WebhookFactory $webhookFactory
     */
    public function __construct(
        public SendEntity $entity,
        public WebhookFactory $webhookFactory,
    ) {}

    /**
     * @return array
     */
    public function middleware(): array
    {
        $rateLimitedMiddleware = (new RateLimited(true))
            ->key('rate-limiter-key:'.$this->entity->getChatId())
            ->allow(env('RATE_LIMITER_ALLOW', 20))
            ->everySeconds(env('RATE_LIMITER_EVERY_SECONDS', 60))
            ->releaseAfterSeconds(env('RATE_LIMITER_RELEASE_AFTER_SECONDS', 30))
        ;

        return [$rateLimitedMiddleware];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->webhookFactory->create($this->entity->hook)->send($this->entity);
    }
}

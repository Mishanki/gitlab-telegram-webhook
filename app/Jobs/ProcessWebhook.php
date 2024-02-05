<?php

namespace App\Jobs;

use App\Services\v1\Webhook\Entity\SendEntity;
use App\Services\v1\Webhook\Factory\WebhookFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessWebhook implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public SendEntity $entity,
        public WebhookFactory $webhookFactory,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->webhookFactory->create($this->entity->hook)->send($this->entity);
    }
}

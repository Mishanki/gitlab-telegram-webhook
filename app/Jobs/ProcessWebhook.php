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
     *
     * @param SendEntity $entity
     * @param WebhookFactory $webhookFactory
     * @param int $sleepBetweenJobs
     */
    public function __construct(
        public SendEntity $entity,
        public WebhookFactory $webhookFactory,
        public int $sleepBetweenJobs,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->webhookFactory->create($this->entity->hook)->send($this->entity);
        if ($this->sleepBetweenJobs) {
            sleep($this->sleepBetweenJobs);
        }
    }
}

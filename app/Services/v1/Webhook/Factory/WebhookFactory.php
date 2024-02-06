<?php

namespace App\Services\v1\Webhook\Factory;

use App\Models\Hook\Enum\HookEnum;
use App\Services\v1\Webhook\JobService;
use App\Services\v1\Webhook\PipelineService;
use App\Services\v1\Webhook\PushService;
use App\Services\v1\Webhook\TagPushService;
use Illuminate\Contracts\Container\BindingResolutionException;

class WebhookFactory
{
    /**
     * @param string $hook
     *
     * @return mixed
     *
     * @throws BindingResolutionException
     */
    public function create(string $hook): WebhookFactoryInterface
    {
        switch ($hook) {
            case HookEnum::HOOK_PUSH->value:
                $service = app()->make(PushService::class);

                break;
            case HookEnum::HOOK_PIPELINE->value:
                $service = app()->make(PipelineService::class);

                break;
            case HookEnum::HOOK_JOB->value:
                $service = app()->make(JobService::class);

                break;
            case HookEnum::HOOK_TAG_PUSH->value:
                $service = app()->make(TagPushService::class);

                break;
        }

        return $service;
    }
}

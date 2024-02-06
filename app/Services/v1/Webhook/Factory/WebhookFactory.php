<?php

namespace App\Services\v1\Webhook\Factory;

use App\Core\Errors;
use App\Exceptions\ValidationException;
use App\Models\Hook\Enum\HookEnum;
use App\Services\v1\Webhook\JobService;
use App\Services\v1\Webhook\MergeRequestService;
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
     * @throws ValidationException
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
            case HookEnum::HOOK_MERGE_REQUEST->value:
                $service = app()->make(MergeRequestService::class);

                break;
            default:
                throw new ValidationException('Hook factory is not found', Errors::VALIDATION_ERROR->value);
        }

        return $service;
    }
}

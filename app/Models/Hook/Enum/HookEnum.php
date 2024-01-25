<?php

namespace App\Models\Hook\Enum;

enum HookEnum: string
{
    case HOOK_PUSH = 'Push Hook';

    case HOOK_PIPELINE = 'Pipeline Hook';

    case HOOK_JOB = 'Job Hook';
}

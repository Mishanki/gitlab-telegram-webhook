<?php

namespace App\Models\Hook\Enum;

enum HookEnum: string
{
    case HOOK_PUSH = 'Push Hook';

    case HOOK_PIPELINE = 'Pipeline Hook';

    case HOOK_JOB = 'Job Hook';

    case HOOK_TAG_PUSH = 'Tag Push Hook';

    case HOOK_MERGE_REQUEST = 'Merge Request Hook';
}

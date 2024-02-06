<?php

namespace App\Models\Hook\Enum;

enum HookEnum: string
{
    case HOOK_PUSH = 'Push Hook';

    case HOOK_PIPELINE = 'Pipeline Hook';

    case HOOK_JOB = 'Job Hook';

    case HOOK_TAG_PUSH = 'Tag Push Hook';

    case HOOK_MERGE_REQUEST = 'Merge Request Hook';

    case HOOK_RELEASE = 'Release Hook';

    case HOOK_ISSUE = 'Issue Hook';

    case HOOK_NOTE = 'Note Hook';

    case HOOK_FEATURE_FLAG = 'Feature Flag Hook';
}

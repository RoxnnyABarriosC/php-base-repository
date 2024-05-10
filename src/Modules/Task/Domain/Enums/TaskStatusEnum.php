<?php

namespace Modules\Task\Domain\Enums;

use Shared\App\Traits\Enum;

enum TaskStatusEnum: string
{
    use Enum;

    case PENDING = 'pending';
    case IN_PROGRESS = 'inProgress';
    case DONE = 'done';
}
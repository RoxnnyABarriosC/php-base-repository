<?php

namespace Modules\Task\Presentation\Criterias;

use Modules\Task\Domain\Enums\TaskStatusEnum;
use Shared\App\Abstract\Criteria;
use Shared\App\Validator\Annotations\Common\IsOptional;
use Shared\App\Validator\Annotations\Transforms\Sanitize;
use Shared\App\Validator\Annotations\Transforms\Trim;
use Shared\App\Validator\Annotations\TypeChecker\IsEnum;
use Shared\App\Validator\Annotations\TypeChecker\IsString;

class TaskFilter extends Criteria
{
    #[IsOptional()]
    #[IsString()]
    #[Sanitize()]
    #[Trim()]
    public mixed $search;


    #[IsOptional()]
    #[IsString()]
    #[IsEnum(TaskStatusEnum::class)]
    public TaskStatusEnum|string|null $status;
}

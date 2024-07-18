<?php

namespace Modules\Task\Presentation\Dto;

use Modules\Task\Domain\Enums\TaskStatusEnum;
use Shared\App\Traits\Magic;
use Shared\App\Validator\Annotations\Common\IsOptional;
use Shared\App\Validator\Annotations\Transforms\Sanitize;
use Shared\App\Validator\Annotations\Transforms\Trim;
use Shared\App\Validator\Annotations\TypeChecker\IsEnum;
use Shared\App\Validator\Annotations\TypeChecker\IsString;


class SaveTaskDto
{
    use Magic;

    #[IsString()]
    #[Trim()]
    #[Sanitize()]
    public mixed $name;

    #[IsOptional()]
    #[IsString()]
    public mixed $description;

    #[IsOptional()]
    #[IsEnum(TaskStatusEnum::class)]
    public mixed $status = TaskStatusEnum::PENDING;
}
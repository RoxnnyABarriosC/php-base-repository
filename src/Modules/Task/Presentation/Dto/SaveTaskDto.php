<?php

namespace Modules\Task\Presentation\Dto;

use Shared\App\Abstract\DTO;
use Respect\Validation\Validator as v;
use Respect\Validation\ChainedValidator;
use Modules\Task\Domain\Enums\TaskStatusEnum;
use Shared\App\Validator\Annotations\IsEnum;
use Shared\App\Validator\Annotations\IsString;

class SaveTaskDto extends DTO
{
    #[IsString()]
    public $name;

    #[IsString()]
    public $description;

    #[IsEnum(TaskStatusEnum::class)]
    public TaskStatusEnum|string|null $status = TaskStatusEnum::PENDING;

    public static function transformProperties(): array
    {
        return [
            'name' => [ToUpperCase(...)],
            'description' => [Sanitize(...), ToUpperCase(...), CleanSpaces(...)],
            'status' => [ToLowerCase(...)]
        ];
    }
}
<?php

namespace Modules\Task\Presentation\Dto;

use Shared\App\Abstract\DTO;
use Respect\Validation\Validator as v;
use Respect\Validation\ChainedValidator;
use Modules\Task\Domain\Enums\TaskStatusEnum;
use Shared\App\Validator\Annotations\Allow;
use Shared\App\Validator\Annotations\IsEnum;
use Shared\App\Validator\Annotations\IsString;




class SaveTaskDto // extends DTO
{
    #[IsString()]
    public $name;

    #[IsString()]
    public $description;

    #[IsEnum(
        enum: TaskStatusEnum::class,
//        each: true
    )]
    #[IsString()]
    public mixed $status = TaskStatusEnum::PENDING;

    #[Allow]
    public mixed $unknown;

}
<?php

namespace Modules\Task\Presentation\Dto;

use Shared\App\Abstract\DTO;
use Respect\Validation\Validator as v;
use Respect\Validation\ChainedValidator;
use Modules\Task\Domain\Enums\TaskStatusEnum;
use Shared\App\Validator\Annotations\Allow;
use Shared\App\Validator\Annotations\IsEnum;
use Shared\App\Validator\Annotations\IsObject;
use Shared\App\Validator\Annotations\IsString;
use Shared\App\Validator\Annotations\Type;
use Shared\App\Validator\Annotations\ValidateNested;


class Meta {

    #[IsString()]
    public $name;

    #[IsString()]
    public $description;

    #[IsObject()]
    #[Type(Meta2::class)]
    #[ValidateNested]
    public mixed $nestedMeta;
}


class Meta2 {

    #[IsString()]
    public $name;

    #[IsString()]
    public $description;
}



class SaveTaskDto // extends DTO
{
    #[IsString()]
    public mixed $name;

    #[IsString()]
    public mixed $description;

    #[IsEnum(
        enum: TaskStatusEnum::class,
//        each: true
    )]
    #[IsString()]
    public mixed $status = TaskStatusEnum::PENDING;

    #[IsObject()]
    #[Type(Meta::class)]
    #[ValidateNested]
    public mixed $meta;

    #[Allow]
    public mixed $unknown;

}
<?php

namespace Modules\Task\Presentation\Dto;

use Modules\Task\Domain\Enums\TaskStatusEnum;
use Shared\App\Validator\Annotations\Common\Allow;
use Shared\App\Validator\Annotations\Common\IsOptional;
use Shared\App\Validator\Annotations\Common\Type;
use Shared\App\Validator\Annotations\Common\ValidateNested;
use Shared\App\Validator\Annotations\TypeChecker\IsArray;
use Shared\App\Validator\Annotations\TypeChecker\IsEnum;
use Shared\App\Validator\Annotations\TypeChecker\IsObject;
use Shared\App\Validator\Annotations\TypeChecker\IsString;


class Meta
{

    #[IsString()]
    public $name;

    #[IsString()]
    public $description;

    #[IsObject()]
    #[Type(Meta2::class)]
    #[ValidateNested]
    public mixed $nestedMeta;
}


class Meta2
{

    #[IsString()]
    public $name;

    #[IsString()]
    public $description;
}


class SaveTaskDto // extends DTO
{

//    #[IsDefined()]
    #[IsString()]
    public mixed $name;

    #[IsString()]
    #[IsOptional()]
    public mixed $description;

    #[IsEnum(
        enum: TaskStatusEnum::class,
        each: true
    )]
    #[IsString(
        each: true
    )]
    public mixed $status = TaskStatusEnum::PENDING;

    #[IsObject(
        each: true
    )]
    #[Type(Meta::class)]
    #[ValidateNested(
        each: true
    )]
    #[IsArray()]
    public mixed $meta;

    #[Allow]
    public mixed $unknown;

}
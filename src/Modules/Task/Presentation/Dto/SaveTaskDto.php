<?php

namespace Modules\Task\Presentation\Dto;

use Modules\Task\Domain\Enums\TaskStatusEnum;
use Shared\App\Validator\Annotations\Common\IsOptional;
use Shared\App\Validator\Annotations\Common\Type;
use Shared\App\Validator\Annotations\Common\ValidateNested;
use Shared\App\Validator\Annotations\String\IsLowercase;
use Shared\App\Validator\Annotations\String\IsNumericString;
use Shared\App\Validator\Annotations\String\IsUUID;
use Shared\App\Validator\Annotations\Transforms\Sanitize;
use Shared\App\Validator\Annotations\Transforms\ToLowerCase;
use Shared\App\Validator\Annotations\Transforms\ToUpperCase;
use Shared\App\Validator\Annotations\Transforms\Trim;
use Shared\App\Validator\Annotations\TypeChecker\IsArray;
use Shared\App\Validator\Annotations\TypeChecker\IsEnum;
use Shared\App\Validator\Annotations\TypeChecker\IsObject;
use Shared\App\Validator\Annotations\TypeChecker\IsString;
use Shared\App\Validator\Annotations\Transforms\Parse as _Parse;


class Meta
{

    #[IsString()]
    #[ToUpperCase()]
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
    #[IsLowercase]
//    #[IsNumericString()]
    #[Trim()]
    #[_Parse()]
    #[ToLowerCase()]
    #[Sanitize()]
    public mixed $name;

    #[IsString()]
    #[IsOptional()]
    public mixed $description;

    #[IsEnum(
        enum: TaskStatusEnum::class,
//        each: true
    )]
    #[IsString(
//        each: true
    )]
//    #[IsArray()]
//    #[ArrayMaxSize(2)]
//    #[ArrayUnique]
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

//    #[Allow]
//    #[IsNumber()]
//    #[IsPositive()]
//    #[Max(100)]
//    #[Min(50)]
//    #[IsUrl]
    #[IsUUID(4)]
    public mixed $unknown;

}
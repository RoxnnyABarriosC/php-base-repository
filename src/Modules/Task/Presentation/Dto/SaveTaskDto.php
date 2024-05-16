<?php

namespace Modules\Task\Presentation\Dto;

use Modules\Task\Domain\Enums\TaskStatusEnum;
use Shared\App\Validator\Annotations\Array\ArrayContains;
use Shared\App\Validator\Annotations\Array\ArrayMaxSize;
use Shared\App\Validator\Annotations\Array\ArrayMinSize;
use Shared\App\Validator\Annotations\Array\ArrayNotContains;
use Shared\App\Validator\Annotations\Array\ArrayNotEmpty;
use Shared\App\Validator\Annotations\Array\ArrayUnique;
use Shared\App\Validator\Annotations\Common\Allow;
use Shared\App\Validator\Annotations\Common\IsDefined;
use Shared\App\Validator\Annotations\Common\IsEmpty;
use Shared\App\Validator\Annotations\Common\IsNotEmpty;
use Shared\App\Validator\Annotations\Common\IsOptional;
use Shared\App\Validator\Annotations\Common\Type;
use Shared\App\Validator\Annotations\Common\ValidateNested;
use Shared\App\Validator\Annotations\Number\IsNegative;
use Shared\App\Validator\Annotations\Number\IsPositive;
use Shared\App\Validator\Annotations\Number\Max;
use Shared\App\Validator\Annotations\Number\Min;
use Shared\App\Validator\Annotations\TypeChecker\IsArray;
use Shared\App\Validator\Annotations\TypeChecker\IsBoolean;
use Shared\App\Validator\Annotations\TypeChecker\IsDate;
use Shared\App\Validator\Annotations\TypeChecker\IsEnum;
use Shared\App\Validator\Annotations\TypeChecker\IsInt;
use Shared\App\Validator\Annotations\TypeChecker\IsNumber;
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
    #[IsArray()]
    #[ArrayMaxSize(2)]
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
    #[IsNumber()]
    #[IsPositive()]
    #[Max(100)]
    #[Min(50)]
    public mixed $unknown;

}
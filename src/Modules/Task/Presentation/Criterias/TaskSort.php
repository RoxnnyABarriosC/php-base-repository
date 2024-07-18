<?php

namespace Modules\Task\Presentation\Criterias;

use Shared\App\Validator\Annotations\Common\IsOptional;
use Shared\App\Validator\Annotations\Transforms\ToUpperCase;
use Shared\App\Validator\Annotations\TypeChecker\IsEnum;
use Shared\Enums\SortEnum;
use Shared\App\Abstract\Criteria;

class TaskSort extends Criteria
{

    #[IsOptional()]
    #[IsEnum(SortEnum::class)]
    #[ToUpperCase()]
    public SortEnum|string|null $name;

    #[IsOptional()]
    #[IsEnum(SortEnum::class)]
    #[ToUpperCase()]
    public SortEnum|string|null $status;


    #[IsOptional()]
    #[IsEnum(SortEnum::class)]
    #[ToUpperCase()]
    public SortEnum|string|null $createdAt = SortEnum::ASC;

    #[IsOptional()]
    #[IsEnum(SortEnum::class)]
    #[ToUpperCase()]
    public SortEnum|string|null $updatedAt;
}
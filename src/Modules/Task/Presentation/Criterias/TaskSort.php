<?php

namespace Modules\Task\Presentation\Criterias;

use Shared\Enums\SortEnum;
use Shared\App\Abstract\Criteria;
use Respect\Validation\Validator as v;
use Respect\Validation\ChainedValidator;

class TaskSort extends Criteria
{
    public SortEnum|string|null $name;
    public SortEnum|string|null $status;
    public SortEnum|string|null $createdAt = SortEnum::ASC;
    public SortEnum|string|null $updatedAt;

    public static function schema(): v|ChainedValidator
    {
        $sort = v::optional(v::stringType()->in([SortEnum::in('value')]));

        return v::attribute('name', $sort)
            ->attribute('status', $sort)
            ->attribute('createdAt', $sort)
            ->attribute('updatedAt', $sort);
    }


    public static function transformProperties(): array
    {
        return [
            'name' => ToUpperCase(...),
            'status' => ToUpperCase(...),
            'createdAt' => ToUpperCase(...),
            'updatedAt' => ToUpperCase(...),
        ];
    }
}
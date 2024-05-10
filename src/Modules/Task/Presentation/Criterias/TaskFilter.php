<?php

namespace Modules\Task\Presentation\Criterias;

use Modules\Task\Domain\Enums\TaskStatusEnum;
use Respect\Validation\ChainedValidator;
use Respect\Validation\Validator as v;
use Shared\App\Abstract\Criteria;

class TaskFilter extends Criteria
{
    public string|null $search;
    public TaskStatusEnum|string|null $status;

    public static function schema(): v|ChainedValidator
    {


//        return v::attribute('search', v::optional(v::stringType()->stringVal()))
//            ->attribute('status', v::optional(v::stringType()->stringVal()));

//        return v::optional(v::attribute('search', v::optional(v::stringType()->stringVal())))
//            ->optional(v::attribute('status', v::optional(v::stringType()->stringVal())));

        return v::optional(v::attribute('search', v::stringType()->stringVal()))
            ->optional(v::attribute('status', v::stringType()->stringVal()));




    }

    public static function transformProperties(): array
    {
        return [
            'search' => [CleanSpaces(...), Sanitize(...)],
            'status' => [ToLowerCase(...)]
        ];
    }
}

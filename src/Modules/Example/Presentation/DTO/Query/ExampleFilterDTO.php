<?php

namespace Modules\Example\Presentation\DTO\Query;

use Respect\Validation\ChainedValidator;
use Respect\Validation\Validator as v;
use Shared\App\Abstract\DTO;

class ExampleFilterDTO extends DTO
{
    public string|null $search;
    public bool|null $isActivated;

    public static function schema(): ChainedValidator
    {
        return v::objectType()
            ->attribute('search', v::optional(v::stringType()->stringVal()))
            ->attribute('isActivated', v::optional(v::boolType()->boolVal()));
    }

    public static function transformProperties(): array
    {
        return [
            'offset' => Parse(...),
            'limit' => Parse(...)
        ];
    }
}

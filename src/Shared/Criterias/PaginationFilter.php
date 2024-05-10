<?php

namespace Shared\Criterias;

use Respect\Validation\ChainedValidator;
use Respect\Validation\Validator as v;
use Shared\App\Abstract\DTO;

class PaginationFilter extends DTO
{
    public int|string $offset = 0;
    public int|string $limit = 10;

    public static function schema(): v|ChainedValidator
    {
//        return v::Attribute('offset', v::Optional(v::intType()->number()))
//            ->Attribute('limit', v::Optional(v::intType()->number()));

        return v::optional(
            v::attribute('offset', v::intType()->number())
                ->attribute('limit', v::intType()->number())
        );
    }

    public static function transformProperties(): array
    {
        return [
            'offset' => Parse(...),
            'limit' => Parse(...)
        ];
    }
}

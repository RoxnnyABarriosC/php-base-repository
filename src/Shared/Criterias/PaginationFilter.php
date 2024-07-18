<?php

namespace Shared\Criterias;

use Shared\App\Validator\Annotations\Common\IsOptional;
use Shared\App\Validator\Annotations\Transforms\Parse;
use Shared\App\Validator\Annotations\TypeChecker\IsNumber;

class PaginationFilter
{

    #[IsOptional()]
    #[IsNumber()]
    #[Parse()]
    public int|string $offset = 0;

    #[IsOptional()]
    #[IsNumber()]
    #[Parse()]
    public int|string $limit = 10;
}

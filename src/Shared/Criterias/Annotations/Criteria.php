<?php

namespace Shared\Criterias\Annotations;

use Attribute;
use Shared\App\Router\Annotations\Query;
use Shared\Criterias\Pipes\CriteriaPipe;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Criteria extends Query
{
    public function __construct()
    {
        parent::__construct(pipes: [CriteriaPipe::class]);
    }
}

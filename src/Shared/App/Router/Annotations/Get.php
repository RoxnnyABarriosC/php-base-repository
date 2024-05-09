<?php

namespace Shared\App\Router\Annotations;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Get extends Route
{
    public function __construct(?string $path = null)
    {
        parent::__construct($path);
    }
}
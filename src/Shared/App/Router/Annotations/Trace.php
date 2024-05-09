<?php

namespace Shared\App\Router\Annotations;

use Attribute;
use Shared\App\Router\Enums\HttpVerbs;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Trace extends Route
{
    public function __construct(string $path)
    {
        parent::__construct($path, HttpVerbs::TRACE);
    }
}
<?php

namespace Shared\App\Router\Annotations;

use Attribute;
use Shared\App\Router\Enums\HttpVerbs;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    public function __construct(
        public ?string   $path = null,
        public HttpVerbs $method = HttpVerbs::GET,
        public array     $middlewares = []
    )
    {
    }
}

<?php

namespace Shared\App\Router\Annotations;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class UseMiddleware
{
    public array $middlewares;

    public function __construct(array ...$middlewares)
    {
        $this->middlewares = $middlewares;
    }
}
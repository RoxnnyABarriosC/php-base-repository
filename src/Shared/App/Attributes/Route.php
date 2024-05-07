<?php

namespace Shared\App\Attributes;

use Attribute;

#[Attribute]
readonly class Route
{
    public function __construct(
        ?string $path = null,
        ?array $middlewares = []
    )
    { }
}

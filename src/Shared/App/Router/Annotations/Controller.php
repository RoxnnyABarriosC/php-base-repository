<?php

namespace Shared\App\Router\Annotations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Controller
{
    public function __construct(
        public ?string $path = null,
        public ?string $version = null)
    {
    }
}

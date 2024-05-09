<?php

namespace Shared\App\Router\Annotations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Module
{
    public function __construct(
        public array $controllers = []
    )
    { }
}

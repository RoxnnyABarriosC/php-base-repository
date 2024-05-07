<?php

namespace Shared\App\Attributes;

use Attribute;

#[Attribute]
readonly class Module
{
    public function __construct(
        array $controllers = []
    )
    { }
}

<?php

namespace Shared\App\Attributes;

use Attribute;
use Shared\App\Enums\HttpVerbs;

#[Attribute]
readonly class Method
{
    public function __construct(HttpVerbs $method)
    { }
}

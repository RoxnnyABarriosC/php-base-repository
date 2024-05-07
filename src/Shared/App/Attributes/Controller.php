<?php

namespace Shared\App\Attributes;

use AllowDynamicProperties;
use Attribute;

#[Attribute]
readonly class Controller
{
    public function __construct(?string $path = null, ?string $version = null)
    {
    }
}

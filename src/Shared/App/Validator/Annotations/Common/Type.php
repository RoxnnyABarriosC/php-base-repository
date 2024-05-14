<?php

namespace Shared\App\Validator\Annotations\Common;

use Attribute;

/**
 * Validate the value is instance of object and execute validation
 *
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Type
{
    public function __construct(
        public mixed $target
    )
    {}
}
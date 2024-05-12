<?php

namespace Shared\App\Validator\Annotations;

use Attribute;

/**
 * Validate the value is instance of object and execute validation
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ValidateNested
{
    public function __construct(
        public bool $each = false
    )
    {    }
}
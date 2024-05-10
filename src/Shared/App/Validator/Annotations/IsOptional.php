<?php

namespace Shared\App\Validator\Annotations;

use Attribute;

/**
 * Declare property as optional (can be uninitialized)
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsOptional
{
}
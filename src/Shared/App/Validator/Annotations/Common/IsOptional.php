<?php

namespace Shared\App\Validator\Annotations\Common;

use Attribute;

/**
 * Declare property as optional (can be uninitialized)
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsOptional
{ }
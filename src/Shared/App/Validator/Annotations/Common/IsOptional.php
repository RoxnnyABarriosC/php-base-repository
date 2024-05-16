<?php

namespace Shared\App\Validator\Annotations\Common;

use Attribute;

/**
 * Class IsOptional
 *
 * This class is a custom attribute used to declare a property of an object as optional.
 * An optional property can be uninitialized.
 *
 * @package Shared\App\Validator\Annotations\Common
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsOptional
{ }
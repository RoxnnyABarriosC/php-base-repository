<?php

namespace Shared\App\Validator\Annotations\Common;

use Attribute;

/**
 * Class Allow
 *
 * This class is a custom attribute used to allow a property of an object to bypass certain validation rules.
 * It can be used on a property to indicate that the property is allowed to have any value.
 *
 * @package Shared\App\Validator\Annotations\Common
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Allow
{ }
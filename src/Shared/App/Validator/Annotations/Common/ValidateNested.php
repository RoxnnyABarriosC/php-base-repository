<?php

namespace Shared\App\Validator\Annotations\Common;

use Attribute;

/**
 * Class ValidateNested
 *
 * This class is a custom attribute used to validate if a property of an object is an instance of another object.
 * If the $each parameter is set to true, it will validate each item in an array to be an instance of the object.
 *
 * @package Shared\App\Validator\Annotations\Common
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ValidateNested
{
    /**
     * ValidateNested constructor.
     *
     * @param bool $each If true, each item in the array should also be an instance of the object.
     */
    public function __construct(
        public bool $each = false
    )
    { }
}
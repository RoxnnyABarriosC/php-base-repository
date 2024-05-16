<?php

namespace Shared\App\Validator\Annotations\Common;

use Attribute;

/**
 * Class Type
 *
 * This class is a custom attribute used to validate if a property of an object is an instance of a specific type.
 * It executes validation on the property based on the target type provided.
 *
 * @package Shared\App\Validator\Annotations\Common
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Type
{
    /**
     * Type constructor.
     *
     * @param mixed $target The target type for validation.
     */
    public function __construct(
        public mixed $target
    )
    {}
}
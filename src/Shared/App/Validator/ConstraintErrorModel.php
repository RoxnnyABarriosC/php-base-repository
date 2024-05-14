<?php

namespace Shared\App\Validator;

/**
 * Class ConstraintErrorModel
 *
 * This class represents a model for constraint errors. It contains information about the property that violated
 * the constraints, the value that was assigned to the property, the constraints that were violated, and any child
 * constraints that were also violated.
 */
class ConstraintErrorModel
{
    /**
     * Constructor for the ConstraintErrorModel class.
     *
     * @param string $property The name of the property that violated the constraints.
     * @param mixed $value The value that was assigned to the property.
     * @param object $constraints The constraints that were violated.
     * @param ConstraintErrorModel[] $children Any child constraints that were also violated.
     */
    public function __construct(
        public string $property,
        public mixed $value,
        public object $constraints,
        public array $children
    )
    { }
}
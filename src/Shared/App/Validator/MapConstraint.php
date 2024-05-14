<?php

namespace Shared\App\Validator;

/**
 * Class MapConstraint
 *
 * This class represents a model for map constraints. It contains information about the value that needs to be validated,
 * and the constraints that should be applied to the value.
 */
class MapConstraint
{
    /**
     * Constructor for the MapConstraint class.
     *
     * @param mixed $value The value that needs to be validated.
     * @param ConstraintErrorModel|null $constraint The constraints that should be applied to the value.
     */
    public function __construct(
        public mixed $value,
        public ConstraintErrorModel|null $constraint,
    )
    { }
}
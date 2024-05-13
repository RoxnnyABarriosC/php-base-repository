<?php

namespace Shared\App\Validator;

/**
 * Class ConstraintModel
 *
 * This class represents a model for constraints. It contains information about the name of the constraint,
 * the message that should be displayed when the constraint is violated, and the language key for localization.
 */
class ConstraintModel
{
    /**
     * Constructor for the ConstraintModel class.
     *
     * @param string $name The name of the constraint.
     * @param string $message The message that should be displayed when the constraint is violated.
     * @param string $langKey The language key for localization.
     */
    public function __construct(
        public string $name,
        public string $message,
        public string $langKey
    )
    { }
}
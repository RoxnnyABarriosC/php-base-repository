<?php

namespace Shared\App\Validator\Annotations\Common;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class IsNotEmpty
 *
 * This class is a custom attribute used to validate if a property of an object is not empty (defined and not null).
 * Optionally, it can also validate if each item in an array is not empty.
 *
 * @package Shared\App\Validator\Annotations\Common
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsNotEmpty implements IValidateConstraint
{
    /**
     * IsNotEmpty constructor.
     *
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be not empty.
     */
    public function __construct(
        public readonly ?string $message = null,
        private readonly bool   $each = false
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * Checks if value is defined (!== undefined, !== null). This is the only decorator that ignores skipMissingProperties option.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is not empty and, if $each is true, each item in the array is also not empty.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => isset($item));
        }

        return isset($value);

    }

    /**
     * Get the default error message.
     *
     * @param ReflectionProperty $property The property that failed validation.
     * @param object $object The object containing the property.
     * @return string Returns the custom error message if it exists, otherwise returns a default error message.
     */
    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        if ($this->message) return $this->message;

        $message = "Property {$property->getName()} must be not empty.";

        return $this->each ? "All values of " . $message : $message;
    }
}
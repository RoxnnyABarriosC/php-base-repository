<?php

namespace Shared\App\Validator\Annotations\String;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class IsBooleanString
 *
 * This class is a custom attribute used to validate if a property of an object is a string that represents a boolean value ('true' or 'false').
 * Optionally, it can also validate if each item in an array is a string that represents a boolean value.
 *
 * @package Shared\App\Validator\Annotations\String
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsBooleanString implements IValidateConstraint
{
    /**
     * IsBooleanString constructor.
     *
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be a string that represents a boolean value.
     */
    public function __construct(
        public readonly ?string $message = null,
        private readonly bool    $each = false
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * Checks if value is a string that represents a boolean value. If $each is true, it checks each item in the array.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is a string that represents a boolean value and, if $each is true, each item in the array is also a string that represents a boolean value.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => ($item === 'true' || $item === 'false'));
        }

        return ($value === 'true' || $value === 'false');
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

        $message = "Property {$property->getName()} must be a string that represents a boolean value";

        return $this->each ? "All values of " . $message : $message;
    }
}
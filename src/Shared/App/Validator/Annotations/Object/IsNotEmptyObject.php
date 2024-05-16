<?php

namespace Shared\App\Validator\Annotations\Object;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class IsNotEmptyObject
 *
 * This class is a custom attribute used to validate if a property of an object is not an empty object.
 * Optionally, it can also validate if each item in an array is not an empty object.
 *
 * @package Shared\App\Validator\Annotations\Object
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsNotEmptyObject implements IValidateConstraint
{
    /**
     * IsNotEmptyObject constructor.
     *
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be not an empty object.
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
     * Checks if value is an object and is not empty. If $each is true, it checks each item in the array.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is not an empty object and, if $each is true, each item in the array is also not an empty object.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_object($item) && !empty((array)$item));
        }

        return is_object($value) && !empty((array)$value);
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

        $message = "Property {$property->getName()} must not be an empty object";

        return $this->each ? "All values of " . $message : $message;
    }
}
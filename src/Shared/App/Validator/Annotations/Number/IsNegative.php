<?php

namespace Shared\App\Validator\Annotations\Number;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class IsNegative
 *
 * This class is a custom attribute used to validate if a property of an object is a negative number.
 * Optionally, it can also validate if each item in an array is a negative number.
 *
 * @package Shared\App\Validator\Annotations\Number
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsNegative implements IValidateConstraint
{
    /**
     * IsNegative constructor.
     *
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be a negative number.
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
     * Checks if value is a number and is less than zero. If $each is true, it checks each item in the array.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is a negative number and, if $each is true, each item in the array is also a negative number.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_numeric($item) && $item < 0);
        }

        return is_numeric($value) && $value < 0;
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

        $message = "Property {$property->getName()} must be a negative number";

        return $this->each ? "All values of " . $message : $message;
    }
}
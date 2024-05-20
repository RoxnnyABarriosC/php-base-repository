<?php

namespace Shared\App\Validator\Annotations\String;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;


/**
 * Check if a string represents a date.
 *
 * @param string $value The string to check.
 * @return bool Returns true if the string represents a date, otherwise returns false.
 */
function is_date_string(string $value): bool
{
    $date = date_parse($value);
    return checkdate($date['month'], $date['day'], $date['year']);
}


/**
 * Class IsDateString
 *
 * This class is a custom attribute used to validate if a property of an object is a string that represents a date.
 * Optionally, it can also validate if each item in an array is a string that represents a date.
 *
 * @package Shared\App\Validator\Annotations\String
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class
IsDateString implements IValidateConstraint
{

    /**
     * IsDateString constructor.
     *
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be a string that represents a date.
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
     * Checks if value is a string that represents a date. If $each is true, it checks each item in the array.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is a string that represents a date and, if $each is true, each item in the array is also a string that represents a date.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_string($item) && is_date_string($item));
        }

        return is_string($value) && is_date_string($value);
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

        $message = "Property {$property->getName()} must be a string that represents a date";

        return $this->each ? "All values of " . $message : $message;
    }
}
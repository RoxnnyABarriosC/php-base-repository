<?php

namespace Shared\App\Validator\Annotations\String;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class MaxLength
 *
 * This class is a custom attribute used to validate if a property of an object is a string with length not exceeding a maximum value.
 * Optionally, it can also validate if each item in an array is a string with length not exceeding a maximum value.
 *
 * @package Shared\App\Validator\Annotations\String
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class MaxLength implements IValidateConstraint
{
    /**
     * MaxLength constructor.
     *
     * @param int $max The maximum length of the string.
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be a string with length not exceeding a maximum value.
     */
    public function __construct(
        private readonly int     $max,
        private readonly ?string $message = null,
        private readonly bool    $each = false
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * Checks if value is a string with length not exceeding a maximum value. If $each is true, it checks each item in the array.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is a string with length not exceeding a maximum value and, if $each is true, each item in the array is also a string with length not exceeding a maximum value.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => self::isValid($item, $this->max));
        }

        return self::isValid($value, $this->max);
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

        $message = "Property {$property->getName()} must be a string with length not exceeding {$this->max}";

        return $this->each ? "All values of " . $message : $message;
    }

    /**
     * Check if a value is a string with length not exceeding a maximum value.
     *
     * @param string $value The value to check.
     * @param int $max The maximum length of the string.
     * @return bool Returns true if the value is a string with length not exceeding a maximum value.
     */
    public static function isValid(string $value, int $max): bool
    {
        $length = mb_strlen($value);
        return $length <= $max;
    }
}
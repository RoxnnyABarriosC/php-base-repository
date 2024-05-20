<?php

namespace Shared\App\Validator\Annotations\String;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class IsEmail
 *
 * This class is a custom attribute used to validate if a property of an object is a valid email.
 * Optionally, it can also validate if each item in an array is a valid email.
 *
 * @package Shared\App\Validator\Annotations\String
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsEmail implements IValidateConstraint
{
    /**
     * IsEmail constructor.
     *
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be a valid email.
     */
    public function __construct(
        private readonly ?string $message = null,
        private readonly bool    $each = false
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * Checks if value is a valid email. If $each is true, it checks each item in the array.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is a valid email and, if $each is true, each item in the array is also a valid email.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => IsEmail::isValid($item));
        }

        return IsEmail::isValid($value);
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

        $message = "Property {$property->getName()} must be a valid email";

        return $this->each ? "All values of " . $message : $message;
    }

    /**
     * Check if a value is a valid email.
     *
     * @param mixed $value The value to check.
     * @return bool Returns true if the value is a valid email.
     */
    static function isValid(mixed $value): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
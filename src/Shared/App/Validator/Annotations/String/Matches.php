<?php

namespace Shared\App\Validator\Annotations\String;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class Matches
 *
 * This class is a custom attribute used to validate if a property of an object matches a given regex pattern.
 * Optionally, it can also validate if each item in an array matches the given regex pattern.
 *
 * @package Shared\App\Validator\Annotations\String
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Matches implements IValidateConstraint
{
    /**
     * Matches constructor.
     *
     * @param string $pattern The regex pattern to match.
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also match the given regex pattern.
     */
    public function __construct(
        private readonly string $pattern,
        private readonly ?string $message = null,
        private readonly bool    $each = false
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * Checks if value matches the given regex pattern. If $each is true, it checks each item in the array.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property matches the given regex pattern and, if $each is true, each item in the array also matches the given regex pattern.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => $this->isValid($item));
        }

        return $this->isValid($value);
    }

    /**
     * Check if a value matches the given regex pattern.
     *
     * @param string $value The value to check.
     * @return bool Returns true if the value matches the given regex pattern.
     */
    private function isValid(string $value): bool
    {
        return (bool) preg_match($this->pattern, $value);
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

        $message = "Property {$property->getName()} must match the pattern {$this->pattern}";

        return $this->each ? "All values of " . $message : $message;
    }
}
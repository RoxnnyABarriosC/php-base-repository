<?php

namespace Shared\App\Validator\Annotations\Array;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class ArrayContains
 *
 * This class is a custom attribute used to validate if a property of an object is an array that contains all the given values.
 * Optionally, it can also validate if each item in an array is also an array that contains all the given values.
 *
 * @package Shared\App\Validator\Annotations\Array
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ArrayContains implements IValidateConstraint
{
    /**
     * ArrayContains constructor.
     *
     * @param array $values The values that should be contained in the array.
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be an array that contains all the given values.
     */
    public function __construct(
        private readonly array   $values,
        private readonly ?string $message = null,
        private readonly bool    $each = false
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is an array that contains all the given values and, if $each is true, each item in the array is also an array that contains all the given values.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => $this->containsAll($item, $this->values));
        }

        return $this->containsAll($value, $this->values);
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

        $values = implode(', ', $this->values);

        $message = "Property {$property->getName()} must contain all the given values ($values)";

        return $this->each ? "All values of " . $message : $message;
    }

    /**
     * Check if an array contains all the given values.
     *
     * @param array $array The array to check.
     * @param array $values The values that should be contained in the array.
     * @return bool Returns true if the array contains all the given values, otherwise returns false.
     */
    private function containsAll(array $array, array $values): bool
    {
        foreach ($values as $value) {
            if (!in_array($value, $array)) {
                return false;
            }
        }

        return true;
    }
}
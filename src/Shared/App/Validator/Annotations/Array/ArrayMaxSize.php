<?php

namespace Shared\App\Validator\Annotations\Array;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class ArrayMaxSize
 *
 * This class is a custom attribute used to validate if a property of an object is an array with a maximum size.
 * Optionally, it can also validate if each item in an array is also an array with a maximum size.
 *
 * @package Shared\App\Validator\Annotations\Array
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ArrayMaxSize implements IValidateConstraint
{
    /**
     * ArrayMaxSize constructor.
     *
     * @param int $max The maximum size of the array.
     * @param bool $each If true, each item in the array should also be an array with a maximum size.
     * @param string|null $message Custom error message.
     */
    public function __construct(
        private readonly int     $max,
        private readonly bool    $each = false,
        private readonly ?string $message = null
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is an array with a maximum size and, if $each is true, each item in the array is also an array with a maximum size.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_array($item) && count($item) <= $this->max);
        }

        return is_array($value) && count($value) <= $this->max;
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

        $message = "Property {$property->getName()} must be an array with a maximum size of {$this->max}";

        return $this->each ? "All values of " . $message : $message;
    }
}
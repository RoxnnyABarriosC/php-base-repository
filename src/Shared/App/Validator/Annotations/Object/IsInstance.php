<?php

namespace Shared\App\Validator\Annotations\Object;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class IsInstance
 *
 * This class is a custom attribute used to validate if a property of an object is an instance of a specified target class.
 * Optionally, it can also validate if each item in an array is an instance of the specified target class.
 *
 * @package Shared\App\Validator\Annotations\Object
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsInstance implements IValidateConstraint
{
    /**
     * IsInstance constructor.
     *
     * @param mixed $target The target class for validation.
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be an instance of the target class.
     */
    public function __construct(
        public readonly mixed   $target,
        public readonly ?string $message = null,
        private readonly bool   $each = false
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * Checks if value is an instance of the specified target class. If $each is true, it checks each item in the array.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is an instance of the target class and, if $each is true, each item in the array is also an instance of the target class.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => $item instanceof $this->target);
        }

        return $value instanceof $this->target;
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

        $message = "Property {$property->getName()} must be an instance of {$this->target}";

        return $this->each ? "All values of " . $message : $message;
    }
}
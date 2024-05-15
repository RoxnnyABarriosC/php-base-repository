<?php

namespace Shared\App\Validator\Annotations\TypeChecker;

use Attribute;
use Exception;
use ReflectionProperty;
use Shared\App\Traits\Enum;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Function to check if a value is a valid enum member.
 *
 * @param mixed $value The value to check.
 * @param Enum $enum The enum to check against.
 * @return bool Returns true if the value is a valid enum member, false otherwise.
 * @throws Exception
 */
function is_enum(mixed $value, mixed $enum): bool
{
    return is_string($value) && $enum::in($value);
}

/**
 * Class IsEnum
 *
 * This class is a custom attribute used to validate if a property of an object is a valid enum member.
 * Optionally, it can also validate if each item in an array is a valid enum member.
 *
 * @package Shared\App\Validator\Annotations\TypeChecker
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsEnum implements IValidateConstraint
{
    /**
     * IsEnum constructor.
     *
     * @param Enum $enum The enum to check against.
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be a valid enum member.
     */
    public function __construct(
        private readonly string $enum,
        public readonly ?string $message = null,
        private readonly bool   $each = false
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is a valid enum member and, if $each is true, each item in the array is also a valid enum member.
     * @throws PropertyException
     * @throws Exception
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = Parse($property->getValue($object));

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_enum($item, $this->enum));
        }

        return is_enum($value, $this->enum);
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
        $message = "Property {$property->getName()} must be a member of enum ({$this->enum::toString()})";

        return $this->each ? "All values of " . $message : $message;
    }
}
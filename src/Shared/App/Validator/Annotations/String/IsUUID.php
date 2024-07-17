<?php

namespace Shared\App\Validator\Annotations\String;

use Attribute;
use Ramsey\Uuid\Uuid;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;

/**
 * Class IsUUID
 *
 * This class is a custom attribute used to validate if a property of an object is a valid UUID.
 * Optionally, it can also validate if each item in an array is a valid UUID.
 *
 * @package Shared\App\Validator\Annotations\String
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsUUID implements IValidateConstraint
{
    /**
     * IsUUID constructor.
     *
     * @param int $version The version of the UUID to validate.
     * @param string|null $message Custom error message.
     * @param bool $each If true, each item in the array should also be a valid UUID.
     */
    public function __construct(
        private readonly int    $version = 1,
        public readonly ?string $message = null,
        private readonly bool   $each = false
    )
    {
    }

    /**
     * Validate the property of an object.
     *
     * Checks if value is a valid UUID. If $each is true, it checks each item in the array.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object containing the property.
     * @return bool Returns true if the property is a valid UUID and, if $each is true, each item in the array is also a valid UUID.
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => self::isValid($item, $this->version));
        }

        return self::isValid($value, $this->version);
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

        $message = "Property {$property->getName()} must be a UUID V{$this->version}";

        return $this->each ? "All values of " . $message : $message;
    }

    /**
     * Check if a value is a valid UUID.
     *
     * @param string $value The value to check.
     * @param int $version The version of the UUID to validate.
     * @return bool Returns true if the value is a valid UUID of the specified version.
     */
    public static function isValid(mixed $value, int $version): bool
    {
        return is_string($value) && Uuid::isValid($value) && Uuid::fromString($value)->getFields()->getVersion() === $version;
    }
}
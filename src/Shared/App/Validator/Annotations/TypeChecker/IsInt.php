<?php

namespace Shared\App\Validator\Annotations\TypeChecker;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;


#[Attribute(Attribute::TARGET_PROPERTY)]
class
IsInt implements IValidateConstraint
{

    public function __construct(
        public readonly ?string $message = null,
        private readonly bool   $each = false
    )
    {
    }

    /**
     * Checks if value is defined (!== undefined, !== null). This is the only decorator that ignores skipMissingProperties option.
     *
     * @throws PropertyException
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_int($item));
        }

        return is_int($value);

    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        if ($this->message) return $this->message;

        $message = "Property {$property->getName()} must be a integer number";

        return $this->each ? "All values of " . $message : $message;
    }
}
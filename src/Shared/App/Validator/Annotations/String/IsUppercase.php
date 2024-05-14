<?php

namespace Shared\App\Validator\Annotations\String;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;


#[Attribute(Attribute::TARGET_PROPERTY)]
class IsUppercase implements IValidateConstraint
{
    public function __construct(
        private readonly ?string $message = null,
        private readonly bool    $each = false

    )
    {
    }


    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return !in_array(false, array_map(fn($item) => is_string($item), $value));
        }

        return is_string($value);
    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        if ($this->message) return $this->message;

        $message = "Property {$property->getName()} must be a string";

        return $this->each ? "All values of " . $message : $message;
    }
}
<?php

namespace Shared\App\Validator\Annotations\TypeChecker;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;


#[Attribute(Attribute::TARGET_PROPERTY)]
class IsObject implements IValidateConstraint
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
            return !in_array(false, array_map(fn($item) => is_object($item), $value));
        }

        return is_object($value);
    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        if ($this->message) return $this->message;

        $message = "Property {$property->getName()} must be a object";

        return $this->each ? "All values of " . $message : $message;
    }
}
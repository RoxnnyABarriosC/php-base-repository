<?php

namespace Shared\App\Validator\Annotations\TypeChecker;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;


#[Attribute(Attribute::TARGET_PROPERTY)]
class IsArray implements IValidateConstraint
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
            return _Array::every($value, fn($item) => is_array($item));
        }

        return is_array($value);
    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        if ($this->message) return $this->message;

        $message = "Property {$property->getName()} must be a array";

        return $this->each ? "All values of " . $message : $message;
    }
}
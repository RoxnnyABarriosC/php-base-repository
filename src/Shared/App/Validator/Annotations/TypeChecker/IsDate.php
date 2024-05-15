<?php

namespace Shared\App\Validator\Annotations\TypeChecker;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;


function is_date(mixed $value): bool
{
    return (bool)strtotime($value);
}


#[Attribute(Attribute::TARGET_PROPERTY)]
class IsDate implements IValidateConstraint
{

    public function __construct(
        public readonly ?string $message = null,
        private readonly bool   $each = false
    )
    {
    }


    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_date($item));
        }


        return is_date($value);

    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        if ($this->message) return $this->message;

        $message = "Property {$property->getName()} must be a date";

        return $this->each ? "All values of " . $message : $message;
    }
}
<?php

namespace Shared\App\Validator\Annotations\String;

use Attribute;
use ReflectionProperty;
use Shared\App\Traits\Enum;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;


#[Attribute(Attribute::TARGET_PROPERTY)]
class IsUrl implements IValidateConstraint
{

    public function __construct(
        public readonly ?string $message = null,
        private readonly bool    $each = false
    )
    {
    }


    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_string($item) && filter_var($item, FILTER_VALIDATE_URL));
        }

        return is_string($value) && filter_var($value, FILTER_VALIDATE_URL);
    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        if ($this->message) return $this->message;

        $message = "Property {$property->getName()} must be a valid URL";

        return $this->each ? "All values of " . $message : $message;
    }
}
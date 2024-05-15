<?php

namespace Shared\App\Validator\Annotations\TypeChecker;

use Attribute;
use Exception;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;


#[Attribute(Attribute::TARGET_PROPERTY)]
class IsString implements IValidateConstraint
{
    public function __construct(
        private readonly ?string $message = null,
        private readonly bool    $each = false

    )
    {
    }


    /**
     * @throws Exception
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = Parse($property->getValue($object));

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_string(Parse($item)));
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
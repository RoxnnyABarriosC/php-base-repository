<?php

namespace Shared\App\Validator\Annotations\TypeChecker;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidateConstraint;


#[Attribute(Attribute::TARGET_PROPERTY)]
class
IsNumber implements IValidateConstraint
{

    public function __construct(
        public readonly ?string $message = null,
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

        if (!is_numeric($value)) {
            return false;
        }

        return true;

    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        return $this->message ?? "Property '{$property->getName()}' must be defined.";
    }
}
<?php

namespace Shared\App\Validator\Annotations\Common;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidateConstraint;


#[Attribute(Attribute::TARGET_PROPERTY)]
class IsNotEmpty implements IValidateConstraint
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

        if ($value) {
            return true;
        }

        return false;

    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        return $this->message ?? "Property '{$property->getName()}' must be defined.";
    }
}
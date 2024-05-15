<?php

namespace Shared\App\Validator\Annotations\TypeChecker;

use Attribute;
use Exception;
use ReflectionProperty;
use Shared\App\Traits\Enum;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Array;


/**
 * @param mixed $value Enum namespace (example: MyEnum::class)
 * @param Enum $enum Enum namespace (example: MyEnum::class)
 * @throws Exception
 */
function is_enum(mixed $value, mixed $enum): bool
{
    return is_string($value) && $enum::in($value);
}

#[Attribute(Attribute::TARGET_PROPERTY)]
class IsEnum implements IValidateConstraint
{
    /**
     * @param Enum $enum Enum namespace (example: MyEnum::class)
     * @param string|null $message
     * @param bool $each
     */
    public function __construct(
        private readonly string $enum,
        public readonly ?string $message = null,
        private readonly bool   $each = false
    )
    {
    }

    /**
     * @throws PropertyException
     * @throws Exception
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = Parse($property->getValue($object));

        if ($this->each && is_array($value)) {
            return _Array::every($value, fn($item) => is_enum($item, $this->enum));
        }

        return is_enum($value, $this->enum);

    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        $message = "Property {$property->getName()} must be a member of enum ({$this->enum::toString()})";

        return $this->each ? "All values of " . $message : $message;
    }
}
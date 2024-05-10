<?php

namespace Shared\App\Validator\Annotations;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidateConstraint;


#[Attribute(Attribute::TARGET_PROPERTY)]
class IsEnum implements IValidateConstraint
{
    /** @param string $enum Enum namespace (example: MyEnum::class) * */
    public function __construct(
        private readonly string  $enum,
        public readonly ?string $message = null,
        private readonly bool    $each = false
    )
    {
    }

    /**
     * @throws PropertyException
     */
    public function validate(ReflectionProperty $property, object $object): bool
    {
        $value = $property->getValue($object);

        if ($this->each && is_array($value)) {
            return !in_array(false, array_map(fn($item) => is_string($item) && in_array($item, $this->enum::in()), $value));
        }

        return is_string($value) && in_array($value, $this->enum::in());
    }

    public function defaultMessage(ReflectionProperty $property, object $object): string
    {
        $message = "Property {$property->getName()} must be a member of enum ({$this->enum::toString()})";

        return $this->each ? "All values of " . $message : $message;
    }
}
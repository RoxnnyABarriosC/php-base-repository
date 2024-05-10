<?php

namespace Shared\App\Validator\Annotations;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidationProperty;

/**
 * Validate value as member of enum value.
 * This will using `tryFrom` enum function.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsEnum implements IValidationProperty
{
    /** @param string $enum Enum namespace (example: MyEnum::class) * */
    public function __construct(
        private string $enum,
    )
    {
    }

    /**
     * @throws PropertyException
     */
    public function validateProperty(ReflectionProperty $property, object $object): void
    {
        $value = $property->getValue($object);

        $match = $this->enum::tryFrom($value);
        if (!$match) {
            throw new PropertyException($property, 'ENUM_INVALID');
        }
    }
}
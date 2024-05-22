<?php

namespace Shared\App\Validator\Annotations\Transforms;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\ITransformValue;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ToUpperCase implements ITransformValue
{

    public function __construct(
        private readonly bool $each = false,
    )
    {
    }

    public function transform(ReflectionProperty $property, object $object, mixed $value): mixed
    {
        $originalValue = $property->getValue($object);

        if (!is_string($value) && !$this->each)
        {
            return $originalValue;
        }

        if ($this->each && is_array($value))
        {
            return array_map(fn($item) => is_string($item) ? strtoupper($item) : $item, $value);
        }


        return strtoupper($value);
    }
}
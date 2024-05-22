<?php

namespace Shared\App\Validator\Annotations\Transforms;

use Attribute;
use Exception;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\ITransformValue;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Parse implements ITransformValue
{


    /**
     * @throws Exception
     */
    public function transform(ReflectionProperty $property, object $object, mixed $value): mixed
    {
        $originalValue = $property->getValue($object);

        if (!is_string($value)) {
            return $originalValue;
        }

        return parse($value);
    }
}
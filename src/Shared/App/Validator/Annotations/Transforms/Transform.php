<?php

namespace Shared\App\Validator\Annotations\Transforms;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Interfaces\ITransformValue;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Transform implements ITransformValue
{
    private readonly mixed $transformer;

    public function __construct(
        callable $transformer
    )
    {
        $this->transformer = $transformer;
    }

    public function transform(ReflectionProperty $property, object $object): void
    {
        $value = $property->getValue($object);
        $property->setValue($object, call_user_func($this->transformer, $value));
    }
}
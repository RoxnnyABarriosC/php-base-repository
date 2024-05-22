<?php

namespace Shared\App\Validator\Interfaces;

use ReflectionProperty;

interface ITransformValue
{
    public function transform(ReflectionProperty $property, object $object, mixed $value): mixed;
}
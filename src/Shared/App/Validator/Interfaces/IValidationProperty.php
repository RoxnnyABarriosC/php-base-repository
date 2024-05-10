<?php

namespace Shared\App\Validator\Interfaces;

use ReflectionProperty;

interface IValidationProperty
{
    public function validateProperty(ReflectionProperty $property, object $object): void;
}
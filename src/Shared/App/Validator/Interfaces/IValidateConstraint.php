<?php

namespace Shared\App\Validator\Interfaces;

use ReflectionProperty;

interface IValidateConstraint
{
    public function validate(ReflectionProperty $property, object $object): bool;

    public function defaultMessage(ReflectionProperty $property, object $object): string;
}
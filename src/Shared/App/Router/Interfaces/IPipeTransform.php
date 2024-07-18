<?php

namespace Shared\App\Router\Interfaces;

interface IPipeTransform
{
     public function transform(mixed $value): mixed;
}
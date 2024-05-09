<?php

namespace Shared\App\Abstract;

use Shared\App\Traits\Magic;
use Shared\App\Traits\Sanitize;
use Respect\Validation\Validator as v;
use Respect\Validation\ChainedValidator;

abstract class DTO
{
    use Magic, Sanitize;

    public static function validate($data): DTO|array
    {
        $data = (new static())->sanitize($data);

        $validator = static::schema();

        $validator->assert(json_decode(json_encode($data)));

        return $data;
    }

    public static function schema(): ChainedValidator
    {
        return v::objectType();
    }
}
<?php

namespace Shared\App\Abstract;

use Shared\App\Traits\Magic;
use Shared\App\Traits\Sanitize;
use Respect\Validation\Validator as v;
use Respect\Validation\ChainedValidator;
use Shared\App\Validator\Exceptions\LocaleException;
use Shared\App\Validator\Validator1;

abstract class DTO
{
    use Magic, Sanitize;

    /**
     * @throws LocaleException
     */
    public static function validate($data): DTO|array|static
    {
        $data = (new static())->sanitize($data);

        Validator1::validate($data);

        return $data;
    }

    public static function schema(): v|ChainedValidator
    {
        return v::objectType();
    }
}
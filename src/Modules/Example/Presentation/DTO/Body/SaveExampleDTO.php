<?php

namespace Modules\Example\Presentation\DTO\Body;

use Respect\Validation\ChainedValidator;
use Respect\Validation\Validator as v;
use Shared\App\Abstract\DTO;

class SaveExampleDTO extends DTO
{
    public string $name;
    public string $description;
    public bool $isActivated = false;

    public static function schema(): v|ChainedValidator
    {
        return
            v::objectType()
                ->attribute('name', v::stringType()->notEmpty())
                ->attribute('description', v::stringType()->notEmpty())
                ->attribute('isActivated', v::optional(v::boolType()));
    }


    public static function transformProperties(): array
    {
        return [
            'name' => [ToUpperCase(...)],
            'description' => [Sanitize(...), ToUpperCase(...), CleanSpaces(...)]
        ];
    }
}
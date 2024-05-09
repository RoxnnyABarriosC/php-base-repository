<?php

namespace Modules\Example\Presentation\DTO;

use Respect\Validation\ChainedValidator;
use Respect\Validation\Validator as v;
use Shared\App\Abstract\DTO;

class SaveExampleDTO extends DTO
{
    public string $name;
    public string $description;
    public bool $isActivated = false;

    public static function schema(): ChainedValidator
    {
        return v::objectType()->attribute('name', v::stringType()->notEmpty())
            ->attribute('description', v::stringType()->notEmpty())
            ->attribute('isActivated', v::optional(v::boolType()));
    }

    public static function transformProperties(): array
    {
        function Sanitize(string $value): string
        {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }

        function ToUpperCase(string $value): string
        {
            return strtoupper($value);
        }

        return [
            'name' => [ToUpperCase(...)],
            'description' => [Sanitize(...), ToUpperCase(...), trim(...)]
        ];
    }
}
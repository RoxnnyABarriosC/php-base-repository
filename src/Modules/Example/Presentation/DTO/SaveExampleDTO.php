<?php

namespace Modules\Example\Presentation\DTO;

use Shared\App\Abstract\DTO;
use Respect\Validation\Validator as v;

class SaveExampleDTO extends DTO
{
    public string $name;
    public string $description;
    public bool $isActivated = false;

    public static function validate($data): DTO|array
    {
        $data = (new static())->sanitize($data);

        $validator = v::objectType()->attribute('name', v::stringType()->notEmpty())
            ->attribute('description', v::stringType()->notEmpty())
            ->attribute('isActivated', v::optional(v::boolType()));

        $validator->assert(json_decode(json_encode($data)));

        return $data;
    }

    public static function transformProperties(): array
    {
        function Sanitize(string $value): string
        {
            return htmlspecialchars($value, ENT_QUOTES,'UTF-8');
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
<?php

namespace Modules\Example\Presentation\DTO\Query;

use Respect\Validation\ChainedValidator;
use Respect\Validation\Validator as v;
use Shared\App\Abstract\DTO;
use Shared\Enums\SortEnum;

class ExampleSortDTO extends DTO
{
    public SortEnum|null $name;
    public SortEnum|null $createdAt = SortEnum::ASC;
    public SortEnum|null $updatedAt;

    public static function schema(): v|ChainedValidator
    {
        $sort = v::optional(v::stringType()->in([SortEnum::ASC, SortEnum::DESC]));

        return v::objectType()
            ->attribute('name', $sort)
            ->attribute('createdAt', $sort)
            ->attribute('updatedAt', $sort);
    }


    public static function transformProperties(): array
    {
        return [
            'date' => ToUpperCase(...)
        ];
    }
}
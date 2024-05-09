<?php

namespace Modules\Example\Domain\UseCases;

use Modules\Example\Domain\Entities\Example;
use Modules\Example\Presentation\DTO\SaveExampleDTO;

class GetExampleUseCase
{

    public static function handle(string $id): Example
    {
        return new Example(
            name: 'Example',
            description: 'Description',
            isActivated: true
        );
    }
}
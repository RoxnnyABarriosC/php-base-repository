<?php

namespace Modules\Example\Domain\UseCases;

use Modules\Example\Domain\Entities\Example;
use Modules\Example\Presentation\DTO\Body\SaveExampleDTO;

class ListExamplesUseCase
{

    public static function handle(SaveExampleDTO $dto): Example
    {
        // $example->description = trim($example->description);
        // throw new HttpException(HttpStatus::BAD_REQUEST, 'Test de error', 'TEST_ERROR');

        return new Example(...$dto->__toArray());
    }
}
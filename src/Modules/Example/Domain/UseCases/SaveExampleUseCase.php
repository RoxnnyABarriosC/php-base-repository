<?php

namespace Modules\Example\Domain\UseCases;

use Exception;
use Modules\Example\Domain\Entities\Example;
use Modules\Example\Presentation\DTO\Body\SaveExampleDTO;
use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Router\Exceptions\HttpException;

class SaveExampleUseCase
{

    public static function handle(SaveExampleDTO $dto): Example
    {
        // $example->description = trim($example->description);
//         throw new HttpException( HttpStatus::BAD_REQUEST,'Test de error', 'TEST_ERROR', );

//        throw new Exception('hola');

        return new Example(...$dto->__toArray());
    }
}
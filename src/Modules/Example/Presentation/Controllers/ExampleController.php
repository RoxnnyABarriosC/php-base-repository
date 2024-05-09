<?php

namespace Modules\Example\Presentation\Controllers;

use JetBrains\PhpStorm\NoReturn;
use Modules\Example\Domain\UseCases\GetExampleUseCase;
use Modules\Example\Domain\UseCases\SaveExampleUseCase;
use Modules\Example\Presentation\DTO\Body\SaveExampleDTO;
use Shared\App\Router\Annotations\Controller;
use Shared\App\Router\Annotations\Get;
use Shared\App\Router\Annotations\Post;
use Shared\App\Router\Annotations\Put;
use Shared\App\Router\Annotations\Route;
use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Router\Enums\HttpVerbs;

#[Controller(
    path: 'example',
    version: 'v1'
)]
class ExampleController
{
//    #[NoReturn]
//    #[Post()]
//    public function save(): void
//    {
//        $dto = SaveExampleDTO::validate(BODY);
//
//        $data = SaveExampleUseCase::handle($dto);
//
//        Response($data, HttpStatus::CREATED);
//    }

    #[NoReturn]
    #[Get(':id')]
    public function get(string $id): void
    {
        $data = GetExampleUseCase::handle($id);

        Response($data);
    }
}
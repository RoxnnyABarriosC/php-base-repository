<?php

namespace Modules\Example\Presentation\Controllers;

use JetBrains\PhpStorm\NoReturn;
use Modules\Example\Domain\UseCases\GetExampleUseCase;
use Modules\Example\Domain\UseCases\SaveExampleUseCase;
use Modules\Example\Presentation\DTO\Body\SaveExampleDTO;
use Shared\App\Attributes\Controller;
use Shared\App\Attributes\Method;
use Shared\App\Attributes\Route;
use Shared\App\Enums\HttpStatus;
use Shared\App\Enums\HttpVerbs;

#[Controller(
    path: 'example',
    version: 'v1'
)]
class ExampleController
{
    #[NoReturn]
    #[Route()]
    #[Method(HttpVerbs::POST)]
    public static function save(): void
    {
        $dto = SaveExampleDTO::validate(BODY);

        $data = SaveExampleUseCase::handle($dto);

        Response($data, HttpStatus::CREATED);
    }

    #[NoReturn]
    #[Route(':id')]
    public static function get(string $id): void
    {
        $data = GetExampleUseCase::handle($id);

        Response($data);
    }
}
<?php

namespace Modules\Example\Presentation\Controllers;

use JetBrains\PhpStorm\NoReturn;
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
    #[Route(path: 'save')]
    #[Method(HttpVerbs::GET)]
    public static function save(): void
    {
//        throw new HttpException(HttpStatus::BAD_REQUEST, 'Test de error', 'TEST_ERROR');
        Response([
            'success' => true,
            'message' => 'Example ====>'
        ], HttpStatus::OK, [
            'metadata' => ['hola' => 'mundo'],
            'pagination' => [
                'total' => 10,
                'limit' => 10,
                'offset' => 0
            ]
        ]);
    }

    #[NoReturn]
    #[Route()]
    #[Method(HttpVerbs::POST)]
    public function main2(): void
    {
//        throw new HttpException(HttpStatus::BAD_REQUEST, 'Test de error', 'TEST_ERROR');
        Response([
            'success' => true,
            'message' => 'Main 2'
        ], HttpStatus::OK, [
            'metadata' => ['hola' => 'mundo'],
            'pagination' => [
                'total' => 10,
                'limit' => 10,
                'offset' => 0
            ]
        ]);
    }
}
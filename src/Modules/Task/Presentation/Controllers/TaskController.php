<?php

namespace Modules\Task\Presentation\Controllers;

use JetBrains\PhpStorm\NoReturn;
use Modules\Task\Domain\UseCases\SaveExampleUseCase;
use Modules\Task\Presentation\Criterias\TaskFilter;
use Modules\Task\Presentation\Criterias\TaskSort;
use Modules\Task\Presentation\Dto\SaveTaskDto;
use Shared\App\Router\Annotations\Controller;
use Shared\App\Router\Annotations\Get;
use Shared\App\Router\Annotations\Post;
use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Validator\Exceptions\LocaleException;
use Shared\App\Validator\Validator;
use Shared\App\Validator\Validator1;
use Shared\Criterias\Criteria;
use Shared\Criterias\PaginationFilter;

#[Controller(
    path: 'tasks',
    version: 'v1'
)]
class TaskController
{
    /**
     * @throws LocaleException
     */
    #[NoReturn]
    #[Post()]
    public function save(): void
    {
        $dto = Validator::validate(BODY, SaveTaskDto::class);

//        var_dump($dto);

//        $data = SaveExampleUseCase::handle($dto);
//
        Response($dto, HttpStatus::CREATED);
    }

//    #[NoReturn]
//    #[Get(':id')]
//    public function get(string $id): void
//    {
//        $data = GetExampleUseCase::handle($id);
//
//        Response($data);
//    }

//    #[NoReturn]
//    #[Get]
//    public function list(): void
//    {
//
//        $criteria = Criteria::build(
//            filter: TaskFilter::class,
//            sort: TaskSort::class,
//            pagination: PaginationFilter::class
//        )->validate(QUERY);
//
////        var_dump($criteria->nestedProperties());
////        var_dump($criteria);
//
//        Response($criteria);
//    }

}
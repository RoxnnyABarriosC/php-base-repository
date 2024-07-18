<?php

namespace Modules\Task\Presentation\Controllers;

use JetBrains\PhpStorm\NoReturn;
use Modules\Task\Domain\UseCases\GetTaskUseCase;
use Modules\Task\Domain\UseCases\SaveTaskUseCase;
use Modules\Task\Presentation\Criterias\TaskFilter;
use Modules\Task\Presentation\Criterias\TaskSort;
use Modules\Task\Presentation\Dto\SaveTaskDto;
use ReflectionException;
use Shared\App\Router\Annotations\Body;
use Shared\App\Router\Annotations\Controller;
use Shared\App\Router\Annotations\Get;
use Shared\App\Router\Annotations\Param;
use Shared\App\Router\Annotations\Post;
use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Validator\Exceptions\LocaleException;
use Shared\App\Validator\Exceptions\ValidationErrorException;
use Shared\Criterias\Annotations\Criteria;
use Shared\Criterias\Criteria as C;
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
    public function save(
        #[Body()] SaveTaskDto $dto,
    ): void
    {
        $data = SaveTaskUseCase::handle($dto);

        Response($data, HttpStatus::CREATED);
    }

    #[NoReturn]
    #[Get(':id')]
    public function get(
        #[Param('id')] $id,
    ): void
    {
        $data = GetTaskUseCase::handle($id);

        Response($data);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationErrorException
     */
    #[NoReturn]
    #[Get]
    public function list(
          #[Criteria()] $criteria
    ): void
    {

        $criteria = C::build(
            $criteria,
            filter: TaskFilter::class,
            sort: TaskSort::class,
            pagination: PaginationFilter::class
        );

        Response($criteria);
    }

}
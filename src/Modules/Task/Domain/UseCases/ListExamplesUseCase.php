<?php

namespace Modules\Task\Domain\UseCases;

use Modules\Task\Domain\Entities\Task;
use Modules\Task\Presentation\Dto\SaveTaskDto;

class ListExamplesUseCase
{

    public static function handle(SaveTaskDto $dto): Task
    {
        // $example->description = trim($example->description);
        // throw new HttpException(HttpStatus::BAD_REQUEST, 'Test de error', 'TEST_ERROR');

        return new Task(...$dto->__toArray());
    }
}
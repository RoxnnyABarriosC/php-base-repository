<?php

namespace Modules\Task\Domain\UseCases;

use Modules\Task\Domain\Entities\Task;
use Modules\Task\Presentation\Dto\SaveTaskDto;

class SaveTaskUseCase
{

    public static function handle(SaveTaskDto $dto): Task
    {
        return new Task(...$dto->__toArray());
    }
}
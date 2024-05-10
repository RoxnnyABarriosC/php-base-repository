<?php

namespace Modules\Task\Domain\UseCases;

use Modules\Task\Domain\Entities\Task;

class GetExampleUseCase
{

    public static function handle(string $id): Task
    {
        return new Task(
            name: 'Task',
            description: 'Description',
            isActivated: true
        );
    }
}
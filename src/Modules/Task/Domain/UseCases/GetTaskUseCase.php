<?php

namespace Modules\Task\Domain\UseCases;

use Modules\Task\Domain\Entities\Task;
use Modules\Task\Domain\Enums\TaskStatusEnum;

class GetTaskUseCase
{

    public static function handle(string $id): Task
    {
        return new Task(
            name: 'Task',
            description: 'Description',
            status: TaskStatusEnum::IN_PROGRESS
        );
    }
}
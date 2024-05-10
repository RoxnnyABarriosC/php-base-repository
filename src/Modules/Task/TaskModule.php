<?php

namespace Modules\Task;

use Shared\App\Router\Enums\HttpVerbs;
use Shared\App\Router\Annotations\Module;
use Shared\App\Router\Traits\Module as TModule;
use Modules\Task\Presentation\Controllers\TaskController;

#[Module(
    controllers: [TaskController::class]
)]
class TaskModule
{
    use TModule;

    public static function onMounted(callable $add): void
    {
        $add('v1/example/health', fn() => Response('API Task is running'), HttpVerbs::GET);
    }
}


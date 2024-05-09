<?php

namespace Modules\Example;

use Shared\App\Router\Enums\HttpVerbs;
use Shared\App\Router\Annotations\Module;
use Shared\App\Router\Traits\Module as TModule;
use Modules\Example\Presentation\Controllers\ExampleController;

#[Module(
    controllers: [ExampleController::class]
)]
class ExampleModule
{
    use TModule;

    public static function onMounted(callable $add): void
    {
        $add('v1/example/health', fn() => Response('API Example is running'), HttpVerbs::GET);
    }
}


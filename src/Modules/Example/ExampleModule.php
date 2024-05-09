<?php

namespace Modules\Example;

use Modules\Example\Presentation\Controllers\ExampleController;
use Shared\App\Router\Annotations\Module;
use Shared\App\Router\Enums\HttpVerbs;
use Shared\App\Router\Traits\Module as TModule;

#[Module(
    controllers: [ExampleController::class]
)]
class ExampleModule
{
    use TModule;

    private static function onMounted(callable $add): void
    {
        $add('/jose', fn() => Response('Jose es bello'), HttpVerbs::GET);
        $add('/henry', fn() => Response('Henry es gay'), HttpVerbs::GET);
    }
}


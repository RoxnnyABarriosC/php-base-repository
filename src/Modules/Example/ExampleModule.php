<?php

namespace Modules\Example;

use Modules\Example\Presentation\Controllers\ExampleController;
use Shared\App\Attributes\Module;
use Shared\App\Enums\HttpVerbs;
use Shared\App\Traits\Module as TModule;

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


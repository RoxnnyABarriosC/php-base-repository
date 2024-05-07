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

//    private static function onMounted(callable $add): void
//    {
//        $add('/example-v2', ExampleController::saveUser(...), HttpVerbs::GET);
//    }
}


<?php

require_once 'autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Shared/App/Utils/Response.php';
require_once __DIR__ . '/Shared/App/Utils/Reflects.php';

use Modules\Example\ExampleModule;
use Shared\App\Core\Router;
use Shared\App\Enums\HttpVerbs;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

Router::add('/health', fn() => Response('API is running'), HttpVerbs::GET);


Router::addModule(ExampleModule::init(...));


Router::run('/api/');
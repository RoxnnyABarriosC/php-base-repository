<?php
require_once 'autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Shared/App/Router/Response.php';
require_once __DIR__ . '/Shared/Utils/Transformers.php';

use Modules\Example\ExampleModule;
use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Router\Router;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

Router::add('/health', fn() => Response('API is running'));

$notFound = fn() => Response('Route not found', HttpStatus::NOT_FOUND);

Router::pathNotFound($notFound);
Router::methodNotAllowed($notFound);

Router::registerModules(ExampleModule::class);

//var_dump(Router::getAll());

Router::build(
    basePath: 'api',
    trailingSlashMatters: false
);

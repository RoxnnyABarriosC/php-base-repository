<?php

require_once 'autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Shared/App/Utils/Response.php';
require_once __DIR__ . '/Shared/App/Utils/Reflects.php';
require_once __DIR__ . '/Shared/Utils/Transformers.php';

use Shared\App\Core\Router;
use Shared\App\Enums\HttpStatus;
use Modules\Example\ExampleModule;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

Router::add('/health', fn() => Response('API is running'));

Router::pathNotFound(fn() => Response('Path not found', HttpStatus::NOT_FOUND));
Router::methodNotAllowed(fn() => Response('Path not found', HttpStatus::NOT_FOUND));

Router::addModule(ExampleModule::init(...));

Router::build(
    basePath: 'api',
    trailingSlashMatters: false
);

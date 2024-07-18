<?php
require_once 'autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Shared/App/Router/Response.php';
require_once __DIR__ . '/Shared/Utils/Transformers.php';
require_once __DIR__ . '/Shared/Utils/index.php';
require_once __DIR__ . '/Shared/App/Validator/Annotations/TypeChecker/IsDate.php';

use Modules\Task\TaskModule;
use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Router\Router;
use Shared\App\Validator\Validator;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

Router::add('health', fn() => Response('API is running'));

$notFound = fn() => Response('Route not found', HttpStatus::NOT_FOUND);

Router::pathNotFound($notFound);
Router::methodNotAllowed($notFound);

Router::registerModules(TaskModule::class);

//echo json_encode(Router::getAll());//

Validator::build(
    whiteList: true,
    forbidNonWhitelisted: true,
    mapError: mapErrors(...)
);

Router::build(
    basePath: '/api',
    caseMatters: true,
    trailingSlashMatters: true,
    enableTypeValidation: true,
    validator: Validator::validate(...)
);

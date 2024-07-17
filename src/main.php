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
use Shared\App\Validator\ConstraintErrorModel;
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

//echo json_encode(Router::getAll());

/**
 * @param ConstraintErrorModel[] $errors
 */
$mapErrors = function (array $errors) {

    $errorModel = function (ConstraintErrorModel $error) use (&$errorModel) {
        $newError = new stdClass();
        $newError->property = $error->property;

        if (!empty((array)$error->constraints)) {
            $newError->constraints = $error->constraints;
        }

        if (!empty($error->children)) {
            $newError->children = [];

            foreach ($error->children as $children) {
                $newError->children[] = $errorModel($children);
            }
        }

        return $newError;
    };

    return array_map($errorModel, $errors);
};


//$originalPath = '^(/api)/v1/tasks/:uuid/status/:status/(.*)$';
//$path = '/api/v1/tasks/123e4567-e89b-12d3-a456-426614174000/status/active/extraSegment';
//
//// Extraer los nombres de las variables del originalPath
//preg_match_all('/:(\w+)/', $originalPath, $matches);
//$variableNames = $matches[1];
//
//// Construir el patrón de expresión regular reemplazando los segmentos :variableName con patrones apropiados
//$pattern = preg_replace_callback('/:(\w+)/', function($match) {
//    return '(?P<' . $match[1] . '>[^/]+)';
//}, $originalPath);
//
//// Añadir patrones de inicio y fin
//$pattern = '~' . $pattern . '~';
//
//// Usar preg_match para obtener los valores coincidentes
//if (preg_match($pattern, $path, $matches)) {
//    $result = [];
//
//    echo json_encode($matches) . PHP_EOL;
//
//    // Filtrar solo los valores coincidentes que son nombres de variables
//    foreach ($variableNames as $name) {
//        $result[$name] = $matches[$name];
//    }
//
//    // Agregar el valor anónimo al resultado
//    $anonymousValues = array_slice($matches, (count($variableNames) * 2) + 2 );
//    foreach ($anonymousValues as $index => $value) {
//        $result[(string)$index] = $value;
//    }
//
//    echo json_encode($result);
//} else {
//    echo "No match found.";
//}


Validator::build(
    whiteList: true,
    forbidNonWhitelisted: true,
    mapError: $mapErrors(...)
);

Router::build(
    basePath: '/api',
    caseMatters: true,
    trailingSlashMatters: true
);

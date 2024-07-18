<?php

use Shared\App\Router\Annotations\Body;
use Shared\App\Router\Annotations\Controller;
use Shared\App\Router\Annotations\Module;
use Shared\App\Router\Annotations\Param;
use Shared\App\Router\Annotations\Query;
use Shared\App\Router\Annotations\Route;
use Shared\App\Router\Annotations\UseMiddleware;
use Shared\App\Router\Enums\HttpVerbs;

/**
 * Add a trailing slash to a given path.
 *
 * @param string|null $path The path to modify.
 * @param bool $isOptional Whether the trailing slash is optional.
 * @return string The modified path.
 */
function AddTrailingSlash(?string $path, bool $isOptional = false): string
{
    if (empty($path) && $isOptional) {
        return '';
    }

    if (!str_starts_with($path, '/')) {
        return '/' . $path;
    }

    return $path;
}

/**
 * Load routes from a given controller.
 *
 * This function uses PHP's reflection API to inspect a given controller class and extract route information from it.
 * It assumes that the controller class and its methods are annotated with custom attributes that define the routes.
 *
 * @param mixed $controller The controller to load routes from.
 * @return array An array of routes.
 * @throws ReflectionClass
 * @throws ReflectionException
 */
function LoadRoute(mixed $controller): array
{
    $basePath = '';
    $routes = [];

    $reflector = new ReflectionClass($controller);

    foreach ($reflector->getAttributes(Controller::class) as $attribute) {
        $iController = $attribute->newInstance();
        $basePath = AddTrailingSlash($iController->version, true) . AddTrailingSlash($iController->path);
    }

    foreach ($reflector->getMethods() as $method) {
        foreach ($method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF) as $rAttribute) {
            $iRoute = $rAttribute->newInstance();

            $route = [
                'path' => $basePath . AddTrailingSlash($iRoute->path, true) ?? '/',
                'method' => $iRoute->method ?? HttpVerbs::GET,
                'middlewares' => $iRoute->middlewares ?? [],
                'function' => $method->isStatic() ? $controller::{
                $method->getName()
                }(...) : $controller->{
                $method->getName()
                }(...)
            ];

            foreach ($method->getAttributes(UseMiddleware::class) as $mAttribute) {
                $iUseMiddleware = $mAttribute->newInstance();
                $route['middlewares'] = array_merge($route['middlewares'], $iUseMiddleware->middlewares);
            }

            $routes[] = $route;
        }
    }

    return $routes;
}

/**
 * Load controllers from a given module.
 *
 * This function uses PHP's reflection API to inspect a given module class and extract controller information from it.
 * It assumes that the module class is annotated with a custom `Module` attribute that defines the controllers.
 *
 * @param mixed $module The module to load controllers from.
 * @return array An array of controllers.
 * @throws ReflectionException
 */
function LoadControllers(mixed $module): array
{
    $routes = [];

    $reflector = new ReflectionClass($module);

    foreach ($reflector->getAttributes(Module::class) as $attribute) {
        foreach ($attribute->newInstance()->controllers as $controller) {
            $routes = array_merge($routes, LoadRoute(new $controller()));
        }
    }

    $module::onMounted(function (string $path, callable $function, $method = HttpVerbs::GET, array $middlewares = NULL) use (&$routes) {
        $routes = array_merge([[
            'path' => AddTrailingSlash($path),
            'function' => $function,
            'method' => $method,
            'middlewares' => $middlewares
        ]], $routes);
    });

    return $routes;
}


/**
 * @throws ReflectionException
 */
function getParamsToControllerMethod(callable $method, ?object $pathParams, ?object $queryParams, ?object $body): array
{
    $reflectorFunction = new ReflectionFunction($method);

    $params = array_fill(0, $reflectorFunction->getNumberOfParameters(), null);

    $targetObjets = [
        Body::class => $body,
        Param::class => $pathParams,
        Query::class => $queryParams
    ];

    foreach ($reflectorFunction->getParameters() as $key => $param) {

        foreach ([Body::class, Param::class, Query::class] as $annotation) {
            $atributes = $param->getAttributes($annotation, ReflectionAttribute::IS_INSTANCEOF);

            foreach ($atributes as $atribute) {
                $params[$key] = ($atribute->newInstance())->handle($targetObjets[$annotation]);
            }
        }
    }

    return $params;
}
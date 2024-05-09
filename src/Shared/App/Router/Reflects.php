<?php

use Shared\App\Router\Annotations\Controller;
use Shared\App\Router\Annotations\Module;
use Shared\App\Router\Annotations\Route;
use Shared\App\Router\Annotations\UseMiddleware;
use Shared\App\Router\Enums\HttpVerbs;


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
 * @throws ReflectionClass
 * @throws ReflectionException
 */
function LoadRoute($controller): array
{
    $basePath = '';
    $routes = [];

    $reflector = new ReflectionClass($controller);

    foreach ($reflector->getAttributes(Controller::class) as $attribute) {
        $iController = $attribute->newInstance();
        $basePath = AddTrailingSlash($iController->version, true) . AddTrailingSlash($iController->path);
    }

    foreach ($reflector->getMethods() as $method) {
        $route = ['path' => '/', 'function' => null, 'method' => HttpVerbs::GET, 'middlewares' => []];
        $isRoutable = false;

        foreach ($method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $isRoutable = true;
            $iRoute = $attribute->newInstance();
            $route['path'] = $basePath . AddTrailingSlash($iRoute->path, true);
            $route['method'] = $iRoute->method;
            $route['middlewares'] = $iRoute->middlewares;

            if ($method->isStatic()) {
                $route['function'] = $controller::{
                $method->getName()
                }(...);
            }

            if (!$method->isStatic()) {
                $route['function'] = $controller->{
                $method->getName()
                }(...);
            }
        }

        foreach ($method->getAttributes(UseMiddleware::class) as $attribute) {
            $iUseMiddleware = $attribute->newInstance();
            $route['middlewares'] = array_merge($route['middlewares'], $iUseMiddleware->middlewares);
        }

        if ($isRoutable) {
            $routes[] = $route;
        }
    }

    return $routes;
}

/**
 * @throws ReflectionException
 */
function LoadControllers($module): array
{
    $routes = [];

    $reflector = new ReflectionClass($module);

    foreach ($reflector->getAttributes(Module::class) as $attribute) {
        $iModule = $attribute->newInstance();

        foreach ($iModule->controllers as $controller) {
            $routes = array_merge($routes, @LoadRoute(new $controller()));
        }
    }

    return $routes;
}
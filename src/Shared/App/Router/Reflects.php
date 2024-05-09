<?php

use Shared\App\Router\Annotations\Controller;
use Shared\App\Router\Annotations\Module;
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
        foreach ($method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $iRoute = $attribute->newInstance();
            $route = [];

            $route['path'] = $basePath . AddTrailingSlash($iRoute->path, true) ?? '/';
            $route['method'] = $iRoute->method ?? HttpVerbs::GET;
            $route['middlewares'] = $iRoute->middlewares ?? [];

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

            $routes[] = $route;
        }

        foreach ($method->getAttributes(UseMiddleware::class) as $attribute) {
            $iUseMiddleware = $attribute->newInstance();
            $routes = array_map(function ($route) use ($iUseMiddleware) {
                $route['middlewares'] = array_merge($route['middlewares'], $iUseMiddleware->middlewares);
                return $route;
            }, $routes);
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
        $iModule = $attribute->newInstance();

        foreach ($iModule->controllers as $controller) {
            $routes = array_merge($routes, @LoadRoute(new $controller()));
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
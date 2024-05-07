<?php

use Shared\App\Attributes\Controller;
use Shared\App\Attributes\Method;
use Shared\App\Attributes\Module;
use Shared\App\Attributes\Route;
use Shared\App\Enums\HttpVerbs;


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

function LoadRoute($controller): array
{
    $basePath = '';
    $routes = [];

    $reflector = new ReflectionObject($controller);

    foreach ($reflector->getAttributes() as $attribute) {
        if ($attribute->getName() === Controller::class) {
            $args = $attribute->getArguments();

            $path = $args['path'] ?? $args[0];
            $version = $args['version'] ?? $args[1];

            $path = AddTrailingSlash($path);
            $version = AddTrailingSlash($version, true);

            $basePath = $version . $path;
        }
    }

    foreach ($reflector->getMethods() as $method) {
        $route = ['expression' => '/', 'function' => null, 'method' => HttpVerbs::GET, 'middlewares' => []];
        $isRoutable = false;

        foreach ($method->getAttributes() as $attribute) {

            if ($attribute->getName() === Route::class) {
                $isRoutable = true;
                $args = $attribute->getArguments();

                $pathMethod = $args['path'] ?? $args[0];
                $middlewares = $args['middlewares'] ?? $args[1] ?? [];

                $route['expression'] = $basePath . AddTrailingSlash($pathMethod, true);

                $route['middlewares'] = $middlewares;

                $route['function'] = $controller->{
                $method->getName()
                }(...);
            }

            if ($attribute->getName() === Method::class) {
                [$route['method']] = $attribute->getArguments();
            }
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

    $reflector = new ReflectionClass($module);

    $routes = [];

    foreach ($reflector->getAttributes() as $attribute) {
        if ($attribute->getName() === Module::class) {
            $args = $attribute->getArguments();

            if (empty($args)) continue;

            $controllers = $args['controllers'] ?? $args[0];

            foreach ($controllers as $controller) {
                $routes = array_merge($routes, @LoadRoute(new $controller()));
            }
        }
    }

    return $routes;
}
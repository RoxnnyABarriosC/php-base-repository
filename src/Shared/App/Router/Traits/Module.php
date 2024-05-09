<?php

namespace Shared\App\Router\Traits;

use ReflectionException;
use Shared\App\Router\Enums\HttpVerbs;


trait  Module
{

    /**
     * The onMounted function
     *
     * This function is called when the module is mounted. It adds the routes for the module.
     *
     * @param callable $add The function to add routes. The function should have the following signature:
     *                      function (string $expression, callable $function, $method = HttpVerbs::GET, array $middlewares = NULL)
     */
    public static function onMounted(callable $add): void
    {}

    /**
     * The init function
     *
     * This function is called to initialize the module. It sets up the routes for the module.
     *
     * @return array The routes for the module.
     * @throws ReflectionException
     */
    public static function init(callable $add): array
    {
        $routes = [];

        self::onMounted(function (string $expression, callable $function, $method = HttpVerbs::GET, array $middlewares = NULL) use (&$routes) {
            $routes[] = array(
                'expression' => $expression,
                'function' => $function,
                'method' => $method,
                'middlewares' => $middlewares
            );
        });

        return array_merge(LoadControllers(self::class), $routes);
    }

}



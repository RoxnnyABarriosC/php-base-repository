<?php

namespace Shared\App\Traits;

use Shared\App\Enums\HttpVerbs;

/**
 * Trait Route
 *
 * This trait provides methods for managing routes in a web application.
 * It allows you to add routes with different HTTP methods and retrieve all added routes.
 *
 * @package Shared\App\Traits
 */
trait Route
{
    /**
     * An array to store all the routes.
     *
     * Each route is an associative array with the following keys:
     * - 'expression': The route pattern (string).
     * - 'function': The callback function to be executed when the route is matched (callable).
     * - 'method': The HTTP method for the route (HttpVerbs).
     * - 'middlewares': An array of middleware to be applied to the route (array|null).
     */
    private static array $routes = array();

    /**
     * Add a new route.
     *
     * This method allows you to add a new route to the application.
     * You can specify the route pattern, the callback function, the HTTP method, and an array of middleware.
     *
     * @param string $expression The route pattern.
     * @param callable $function The callback function to be executed when the route is matched.
     * @param HttpVerbs $method The HTTP method for the route. Defaults to GET.
     * @param array|null $middlewares An array of middleware to be applied to the route. Defaults to NULL.
     */
    public static function add(string $expression, callable $function, HttpVerbs $method = HttpVerbs::GET, array $middlewares = NULL): void
    {
        self::$routes[] = array(
            'expression' => $expression,
            'function' => $function,
            'method' => $method,
            'middlewares' => $middlewares
        );
    }

    /**
     * Get all routes.
     *
     * This method returns an array of all routes that have been added to the application.
     *
     * @return array An array of all routes.
     */
    public static function getAll(): array
    {
        return self::$routes;
    }
}
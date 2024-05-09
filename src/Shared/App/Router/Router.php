<?php

namespace Shared\App\Router;

use Shared\App\Router\Traits\Route;

/**
 * Class Router
 *
 * This class is used to manage routes in a web application.
 * It provides methods to add routes, handle path not found and method not allowed scenarios.
 */
class Router
{

    use Route;

    /**
     * @var callable|null $pathNotFound Callback function to handle path not found scenario
     */
    private static $pathNotFound = null;

    /**
     * @var callable|null $methodNotAllowed Callback function to handle method not allowed scenario
     */
    private static $methodNotAllowed = null;

    /**
     * Set the callback function for path not found scenario
     *
     * @param callable $function Callback function
     */
    public static function pathNotFound(callable $function): void
    {
        self::$pathNotFound = $function;
    }

    /**
     * Set the callback function for method not allowed scenario
     *
     * @param callable $function Callback function
     */
    public static function methodNotAllowed(callable $function): void
    {
        self::$methodNotAllowed = $function;
    }

    private static function processRoute($route): array|string|null
    {
        return preg_replace('/:\w+/', '(\w+)', $route);
    }

    /**
     * Build and run the router.
     *
     * This method parses the request URI, matches it against the registered routes,
     * and calls the appropriate callback function. If no match is found, it calls
     * the path not found or method not allowed callback function as appropriate.
     *
     * @param string $basePath Base path for the routes
     * @param bool $caseMatters Whether the route matching should be case-sensitive
     * @param bool $trailingSlashMatters Whether trailing slashes should be considered in route matching
     * @param bool $multiMatch Whether multiple routes should be matched
     */
    public static function build(string $basePath = '', bool $caseMatters = false, bool $trailingSlashMatters = false, bool $multiMatch = false): void
    {
        // Ensure the base path ends with a slash
        $basePath = AddTrailingSlash(rtrim($basePath, '/'), true);

        // Parse the current request URI
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);

        // Default path is root
        $path = '/';

        // If a path is set in the parsed URL, use it
        if (isset($parsed_url['path'])) {
            $path = $trailingSlashMatters ? $parsed_url['path'] : rtrim($parsed_url['path'], '/');
        }

        // Decode the path
        $path = urldecode($path);

        // Get the request method
        $method = $_SERVER['REQUEST_METHOD'];

        // Initialize match flags
        $pathMatchFound = false;
        $routeMatchFound = false;

        // Iterate over all registered routes
        foreach (self::$routes as $route) {
            // Prepare the route path for matching
            $route['path'] = '^(' . $basePath . ')' . $route['path'] . '$';

            // Process the route path
            $route['path'] = self::processRoute($route['path']);

            // Try to match the route
            if (preg_match('#' . $route['path'] . '#' . ($caseMatters ? '' : 'i') . 'u', $path, $matches)) {
                $pathMatchFound = true;

                // Check if the request method matches
                foreach ((array)$route['method'] as $allowedMethod) {
                    if (strcasecmp($method, $allowedMethod) == 0) {
                        // Extract the matches and call the route function
                        $matches = array_slice($matches, $basePath == '' || $basePath == '/' ? 1 : 2);

                        // If the route function returns a value, echo it
                        if ($return_value = call_user_func_array($route['function'], $matches)) {
                            echo $return_value;
                        }

                        // Mark the route as found
                        $routeMatchFound = true;
                        break;
                    }
                }
            }

            // If a route was found and we're not matching multiple routes, break the loop
            if ($routeMatchFound && !$multiMatch) {
                break;
            }
        }

        // If no route was found, call the appropriate callback
        if (!$routeMatchFound) {
            $callback = $pathMatchFound ? self::$methodNotAllowed : self::$pathNotFound;
            if ($callback) {
                $args = $pathMatchFound ? array($path, $method) : array($path);
                $return_value = call_user_func_array($callback, $args);
                if ($return_value) {
                    echo $return_value;
                }
            }
        }
    }
}

define("BODY", json_decode(file_get_contents('php://input'), true) ?? []);

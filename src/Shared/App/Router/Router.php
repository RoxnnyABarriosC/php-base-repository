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
        $basePath = AddTrailingSlash(rtrim($basePath, '/'), true);
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);
        $path = isset($parsed_url['path']) ? ($trailingSlashMatters ? $parsed_url['path'] : rtrim($parsed_url['path'], '/')) : '/';
        $path = urldecode($path);
        $method = $_SERVER['REQUEST_METHOD'];

        $pathMatchFound = false;
        $routeMatchFound = false;

        foreach (self::$routes as $route) {
            $route['path'] = '^(' . $basePath . ')' . self::processRoute($route['path']) . '$';

            if (preg_match('#' . $route['path'] . '#' . ($caseMatters ? '' : 'i') . 'u', $path, $matches)) {
                $pathMatchFound = true;

                if (in_array($method, (array)$route['method'], true)) {
                    echo call_user_func_array($route['function'], array_slice($matches, $basePath == '' || $basePath == '/' ? 1 : 2)) ?: '';
                    $routeMatchFound = true;
                }

                if ($routeMatchFound && !$multiMatch) {
                    break;
                }
            }
        }

        if (!$routeMatchFound) {
            $callback = $pathMatchFound ? self::$methodNotAllowed : self::$pathNotFound;
            echo call_user_func_array($callback, [$path, $method]) ?: '';
        }
    }
}

define("BODY", json_decode(file_get_contents('php://input'), true) ?? []);
define("QUERY", $_GET);

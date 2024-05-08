<?php

namespace Shared\App\Core;

use Shared\App\Traits\Route;

class Router
{

    use Route;

    private static $pathNotFound = null;
    private static $methodNotAllowed = null;


    public static function addModule(callable $module): void
    {
        self::$routes = array_merge(self::$routes, $module());

//        var_dump(self::$routes);
    }

    public static function addMany(array $routes): void
    {
        self::$routes = array_merge(self::$routes, $routes);
    }

    public static function pathNotFound(callable $function): void
    {
        self::$pathNotFound = $function;
    }

    public static function methodNotAllowed(callable $function): void
    {
        self::$methodNotAllowed = $function;
    }

    public static function run(string $basePath = '', bool $caseMatters = false, bool $trailingSlashMatters = false, bool $multiMatch = false): void
    {
        $basePath = AddTrailingSlash(rtrim($basePath, '/'), true);

        // Parse current URL
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);

        $path = '/';

        if (isset($parsed_url['path'])) {
            $path = $trailingSlashMatters ? $parsed_url['path'] : rtrim($parsed_url['path'], '/');
        }

        $path = urldecode($path);

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

        $pathMatchFound = false;
        $routeMatchFound = false;

        foreach (self::$routes as $route) {
            $route['expression'] = '^(' . $basePath . ')' . $route['expression'] . '$';

            if (preg_match('#' . $route['expression'] . '#' . ($caseMatters ? '' : 'i') . 'u', $path, $matches)) {
                $pathMatchFound = true;

                foreach ((array)$route['method'] as $allowedMethod) {
                    if (strcasecmp($method, $allowedMethod) == 0) {
                        $matches = array_slice($matches, $basePath == '' || $basePath == '/' ? 1 : 2);

                        if ($return_value = call_user_func_array($route['function'], $matches)) {
                            echo $return_value;
                        }

                        $routeMatchFound = true;
                        break;
                    }
                }
            }

            if ($routeMatchFound && !$multiMatch) {
                break;
            }
        }

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
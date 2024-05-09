<?php

namespace Shared\App\Router\Traits;

/**
 * Trait Module
 *
 * This trait provides a method for handling the mounting of a module in the application.
 * It can be used in a class that represents a module to define what happens when the module is mounted.
 *
 * @package Shared\App\Router\Traits
 */
trait  Module
{
    /**
     * The onMounted function
     *
     * This function is called when the module is mounted. It adds the routes for the module.
     * The function to add routes is passed as a parameter. This function should have the following signature:
     * function (string $expression, callable $function, $method = HttpVerbs::GET, array $middlewares = NULL)
     *
     * @param callable $add The function to add routes.
     */
    public static function onMounted(callable $add): void
    {}
}
<?php

namespace Shared\App\Router\Annotations;

use Attribute;

/**
 * Class UseMiddleware
 *
 * This class is a custom attribute used to define a middleware for a route in the application.
 * It can be used to annotate methods in a controller class.
 * The middleware can be customized with an array of middlewares.
 *
 * @package Shared\App\Router\Annotations
 */
#[Attribute(Attribute::TARGET_METHOD)]
class UseMiddleware
{
    /**
     * @var array The array of middlewares for the route.
     */
    public array $middlewares;

    /**
     * UseMiddleware constructor.
     *
     * Constructs a new instance of the UseMiddleware attribute.
     * The constructor takes an array of middlewares as parameters.
     *
     * @param array ...$middlewares The middlewares for the route.
     */
    public function __construct(array ...$middlewares)
    {
        $this->middlewares = $middlewares;
    }
}
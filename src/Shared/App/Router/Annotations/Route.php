<?php

namespace Shared\App\Router\Annotations;

use Attribute;
use Shared\App\Router\Enums\HttpVerbs;

/**
 * Class Route
 *
 * This class is a custom attribute used to define a route in the application.
 * It can be used to annotate methods in a controller class.
 * The route can be customized with a path, an HTTP verb, and an array of middlewares.
 *
 * @package Shared\App\Router\Annotations
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    /**
     * Route constructor.
     *
     * Constructs a new instance of the Route attribute.
     * The constructor takes a path, an HTTP verb, and an array of middlewares as parameters.
     * The path and the middlewares are optional. If no path is provided, the default path will be used.
     * If no HTTP verb is provided, the default verb will be GET.
     * If no middlewares are provided, the default middlewares will be used.
     *
     * @param string|null $path The path for the route. Optional.
     * @param HttpVerbs $method The HTTP verb for the route. Default is GET.
     * @param array $middlewares The middlewares for the route. Optional.
     */
    public function __construct(
        public ?string   $path = null,
        public HttpVerbs $method = HttpVerbs::GET,
        public array     $middlewares = []
    )
    { }
}
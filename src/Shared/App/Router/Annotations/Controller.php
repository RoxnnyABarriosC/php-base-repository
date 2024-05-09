<?php

namespace Shared\App\Router\Annotations;

use Attribute;

/**
 * Class Controller
 *
 * This class is a custom attribute used to define a Controller in the application.
 * It can be used to annotate classes that should be treated as Controllers.
 *
 * @package Shared\App\Router\Annotations
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Controller
{
    /**
     * Controller constructor.
     *
     * Constructs a new instance of the Controller attribute.
     * The constructor takes a path and a version as parameters.
     *
     * @param string|null $path The base path for the routes in the Controller.
     * @param string|null $version The version of the API for the Controller.
     */
    public function __construct(
        public ?string $path = null,
        public ?string $version = null)
    { }
}
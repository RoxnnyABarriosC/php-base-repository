<?php

namespace Shared\App\Router\Annotations;

use Attribute;

/**
 * Class Get
 *
 * This class is a custom attribute used to define a GET route in the application.
 * It extends the base Route attribute and sets the HTTP verb to GET by default.
 * It can be used to annotate methods in a controller class.
 *
 * @package Shared\App\Router\Annotations
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Get extends Route
{
    /**
     * Get constructor.
     *
     * Constructs a new instance of the Get attribute.
     * The constructor takes a path as an optional parameter and passes it to the parent Route constructor.
     * If no path is provided, the parent Route constructor will use the default path.
     *
     * @param string|null $path The path for the GET route. Optional.
     */
    public function __construct(?string $path = null)
    {
        parent::__construct($path);
    }
}
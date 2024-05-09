<?php

namespace Shared\App\Router\Annotations;

use Attribute;
use Shared\App\Router\Enums\HttpVerbs;

/**
 * Class Put
 *
 * This class is a custom attribute used to define a PUT route in the application.
 * It extends the base Route attribute and sets the HTTP verb to PUT.
 * It can be used to annotate methods in a controller class.
 *
 * @package Shared\App\Router\Annotations
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Put extends Route
{
    /**
     * Put constructor.
     *
     * Constructs a new instance of the Put attribute.
     * The constructor takes a path as an optional parameter and passes it along with the PUT HTTP verb to the parent Route constructor.
     * If no path is provided, the parent Route constructor will use the default path.
     *
     * @param string|null $path The path for the PUT route. Optional.
     */
    public function __construct(?string $path = null)
    {
        parent::__construct($path, HttpVerbs::PUT);
    }
}
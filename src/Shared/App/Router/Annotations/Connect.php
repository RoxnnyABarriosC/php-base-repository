<?php

namespace Shared\App\Router\Annotations;

use Attribute;
use Shared\App\Router\Enums\HttpVerbs;

/**
 * Class Connect
 *
 * This class is a custom attribute used to define a CONNECT route in the application.
 * It extends the base Route attribute and sets the HTTP verb to CONNECT.
 * It can be used to annotate methods in a controller class.
 *
 * @package Shared\App\Router\Annotations
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Connect extends Route
{
    /**
     * Connect constructor.
     *
     * Constructs a new instance of the Connect attribute.
     * The constructor takes a path as a parameter and passes it along with the CONNECT HTTP verb to the parent Route constructor.
     *
     * @param string $path The path for the CONNECT route.
     */
    public function __construct(string $path)
    {
        parent::__construct($path, HttpVerbs::CONNECT);
    }
}
<?php

namespace Shared\App\Router\Annotations;

use Attribute;
use Shared\App\Router\Enums\HttpVerbs;

/**
 * Class Post
 *
 * This class is a custom attribute used to define a POST route in the application.
 * It extends the base Route attribute and sets the HTTP verb to POST.
 * It can be used to annotate methods in a controller class.
 *
 * @package Shared\App\Router\Annotations
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Post extends Route
{
    /**
     * Post constructor.
     *
     * Constructs a new instance of the Post attribute.
     * The constructor takes a path as an optional parameter and passes it along with the POST HTTP verb to the parent Route constructor.
     * If no path is provided, the parent Route constructor will use the default path.
     *
     * @param string|null $path The path for the POST route. Optional.
     */
    public function __construct(?string $path = null)
    {
        parent::__construct($path, HttpVerbs::POST);
    }
}
<?php

namespace Shared\App\Router\Annotations;

use Attribute;

/**
 * Class Module
 *
 * This class is a custom attribute used to define a Module in the application.
 * It can be used to annotate classes that should be treated as Modules.
 * A Module is a logical grouping of Controllers.
 *
 * @package Shared\App\Router\Annotations
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Module
{
    /**
     * Module constructor.
     *
     * Constructs a new instance of the Module attribute.
     * The constructor takes an array of Controllers as a parameter.
     * These Controllers are part of the Module.
     *
     * @param array $controllers The Controllers that are part of the Module.
     */
    public function __construct(
        public array $controllers = []
    )
    { }
}
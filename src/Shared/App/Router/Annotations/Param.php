<?php

namespace Shared\App\Router\Annotations;

use Attribute;
use Shared\Utils\_Object;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Param
{

    public function __construct(
        public ?string $path = null,
    )
    { }

    public function handle(object $object): mixed
    {
        return _Object::path($object, $this->path);
    }
}
<?php

namespace Shared\App\Router\Annotations;

use Attribute;
use Shared\App\Router\Interfaces\IPipeTransform;
use Shared\App\Router\Traits\Pipes;
use Shared\Utils\_Object;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Param
{

    use Pipes;

    /**
     * Body constructor.
     *
     * @param ?string $path
     * @param IPipeTransform[]|string[]|null $pipes
     */
    public function __construct(
        public ?string $path = null,
        public ?array  $pipes = null
    )
    {
    }

    public function handle(object $object): mixed
    {
        return $this->resolvePipes(_Object::path($object, $this->path));
    }
}
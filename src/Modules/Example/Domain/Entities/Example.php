<?php

namespace Modules\Example\Domain\Entities;

use Shared\App\Abstract\Entity;

class Example extends Entity
{
    public string $name;
    public string $description;
    public bool $isActivated;

    public function __construct(...$args)
    {
        parent::__construct();

        $this->build($args);
    }

}
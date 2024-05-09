<?php

namespace Shared\App\Abstract;

use Ramsey\Uuid\Uuid;
use Respect\Validation\Rules\Date;
use Shared\App\Traits\Magic;
use Shared\App\Traits\Util;

abstract class Entity
{
    use Magic, Util;

    public string $_id;
    public string|Date|int $createdAt;
    public string|Date|int|null $updatedAt;
    public string|Date|int|null $deletedAt;

    public function __construct()
    {
        $this->_id = Uuid::uuid4()->toString();
    }
}
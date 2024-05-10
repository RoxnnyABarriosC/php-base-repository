<?php

namespace Modules\Task\Domain\Entities;

use Shared\App\Abstract\Entity;
use Modules\Task\Domain\Enums\TaskStatusEnum;

class Task extends Entity
{
    public string $name;
    public ?string $description;
    public TaskStatusEnum|string|array $status;

    public function __construct(...$args)
    {
        parent::__construct();

        $this->build($args);
    }

}
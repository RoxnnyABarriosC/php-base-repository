<?php

namespace Shared\Enums;

use Shared\App\Traits\Enum;

enum SortEnum: string
{
    use Enum;

    case ASC = 'ASC';
    case DESC = 'DESC';
}
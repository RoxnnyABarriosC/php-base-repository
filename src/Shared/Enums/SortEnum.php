<?php

namespace Shared\Enums;

use Shared\App\Traits\Enum;

enum SortEnum
{
    use Enum;

    case ASC;
    case DESC;
}
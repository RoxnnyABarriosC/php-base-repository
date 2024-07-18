<?php

namespace Shared\Criterias\Pipes;

use Shared\App\Router\Interfaces\IPipeTransform;

class CriteriaPipe implements IPipeTransform
{
    public function transform(mixed $value): mixed
    {
        foreach (['filter', 'sort', 'pagination'] as $key) {
            if (!isset($value->$key)) {
                $value->$key = new \stdClass();
            }
        }

        return $value;
    }
}

<?php

namespace Shared\App\Router\Traits;


use Shared\App\Router\Interfaces\IPipeTransform;


trait Pipes
{

    public function resolvePipes(mixed $value): mixed
    {

        if (!is_null($this->pipes) && count($this->pipes)) {
            foreach ($this->pipes as $pipe) {
                if (!$pipe instanceof IPipeTransform) {
                    $pipe = new $pipe();
                }
                $value = $pipe->transform($value);
            }
        }

        return $value;
    }

}
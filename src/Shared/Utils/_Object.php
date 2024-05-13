<?php

namespace Shared\Utils;

class _Object
{


    public static function assign(object $target, object $object): object
    {
        foreach ($object as $property => $value) {
            $target->$property = $value;
        }


        return $target;
    }
}
<?php

namespace Shared\Utils;

class _Array
{


   // crea una funcion every que verifica si todos los elementos de un array cumplen con una condicion
    public static function every(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if (!$callback($value, $key)) {
                return false;
            }
        }
        return true;
    }

}
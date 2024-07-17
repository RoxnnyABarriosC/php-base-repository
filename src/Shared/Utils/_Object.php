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

    public static function path(object $object, ?string $path = null): mixed
    {
        if (is_null($path)) {
            return $object;
        }

        $paths = explode('.', $path);

        $value = $object;

        foreach ($paths as $_path) {

            if (preg_match('/\[(\d+)\]/', $_path, $matches)) {
                $_path = (int)$matches[1];
            }

            if (is_int($_path) && is_array($value) && array_key_exists($_path, $value)) {
                $value = $value[$_path];
                continue;
            }

            if (is_array($value) || !property_exists($value, $_path)) {
                $value = null;
                break;
            }

            $value = $value?->$_path;
        }

        return $value;
    }
}
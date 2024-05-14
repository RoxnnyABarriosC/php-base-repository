<?php

namespace Shared\App\Traits;

use Exception;

trait Enum
{

    public static function array(string $get = 'value'): array
    {
        return array_reduce(static::cases(), function ($carry, $case) use ($get) {
            $carry[] = $case?->$get ?? null;
            return $carry;
        }, []);
    }

    public static function toString(string $get = 'value'): string
    {
        return implode(', ', static::array($get));
    }

    /**
     * @throws Exception
     */
    public static function in(mixed $value, $get = 'value'): bool
    {
        return in_array($value, self::array($get));
    }
}
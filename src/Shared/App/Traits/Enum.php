<?php

namespace Shared\App\Traits;

trait Enum
{
    public static function in(string $get = 'value'): array
    {
        return array_reduce(static::cases(), function ($carry, $case) use ($get) {
            $carry[] = $case?->$get ?? null;
            return $carry;
        }, []);
    }

    public static function toString(string $get = 'value'): string
    {
        return implode(', ', static::in($get));
    }
}
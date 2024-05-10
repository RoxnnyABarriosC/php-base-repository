<?php

namespace Shared\App\Validator;

use ReflectionClass;
use ReflectionProperty;

class PropertyErrors
{
    protected static array $errors = [];

    public function __construct(ReflectionProperty $property, string $message, string $key)
    {
//        var_dump($property);


        self::$errors[] = [
            'property' => $property->getName(),
            'constraints' => [
                (new ReflectionClass($this))->getName() => [
                    'message' => $message,
                    'key' => $key
                ]
            ]
        ];
    }

    public static function getErrors(): array
    {
        return self::$errors;
    }

    public static function clearErrors(): void
    {
        self::$errors = [];
    }
}
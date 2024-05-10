<?php

namespace Shared\App\Validator\Exceptions;

use Exception;
use ReflectionClass;
use ReflectionProperty;
use Shared\App\Validator\Annotations\Name;

use function implode;
use function preg_split;
use function sprintf;
use function ucwords;

//final class PropertyException extends Exception
//{
//    /** @var array<string, string> $messageList */
//    protected static array $messageList;
//
//    protected static array $errors = [];
//
//    public function __construct(ReflectionProperty $property, string $langKey, mixed ...$additionalSprint)
//    {
//        $nameAttributes = $property->getAttributes(Name::class);
//        if (@$nameAttributes[0]) {
//            $nameAttribute = $nameAttributes[0];
//            $args          = $nameAttribute->getArguments();
//            $name          = @$args[0] ? $args[0] : @$args['name'];
//        }
//
//        if (! @$name) {
//            $name = $property->getName();
//            $name = ucwords(implode(' ', preg_split('/(?=[A-Z])/', $name)));
//        }
//
//        $defaultMsg = '%s is invalid!';
//
//        $fmt = sprintf(static::$messageList[$langKey] ?? $defaultMsg, $name, ...$additionalSprint);
//
//        parent::__construct($fmt);
//    }
//
//    /** @param array<string, string> $messageList */
//    public static function setMessageList(array $messageList): void
//    {
//        static::$messageList = $messageList;
//    }
//}

final class PropertyException extends Exception
{
    protected static array $errors = [];

    public function __construct(ReflectionProperty $property, string $key)
    {
        self::$errors[] = [
            'property' => $property->getName(),
            'constraints' => [
                (new ReflectionClass($this))->getShortName() => [
                    'message' => $this->getMessage(),
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
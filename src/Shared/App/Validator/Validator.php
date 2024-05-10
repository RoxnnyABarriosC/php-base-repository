<?php

namespace Shared\App\Validator;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Shared\App\Validator\Annotations\IsOptional;
use Shared\App\Validator\Exceptions\LocaleException;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidationProperty;
use Throwable;

class Validator
{
    protected static string $lang;
    protected static string $langDir;

    /**
     * Validate object class
     *
     * @param object $object Object that will be validated
     *
     * @return bool Success result
     */
    public static function validate(object $object): bool
    {
        if (!isset(static::$langDir)) {
            static::$langDir = __DIR__ . '/../locale';
        }

        if (!isset(static::$lang)) {
            static::$lang = 'id';
        }

        $langFile = static::$langDir . '/' . static::$lang . '.php';
        try {
            $locales = require $langFile;
        } catch (Throwable $th) {
            throw new LocaleException($th->getMessage(), $th->getCode(), $th);
        }

        PropertyException::setMessageList($locales);

        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            self::validateProperty($property, $object);
        }

        return true;
    }

    protected static function validateProperty(ReflectionProperty $property, object $object): void
    {
        if (!$property->isInitialized($object) || $property->getValue($object) === '' || $property->getValue($object) === null) {
            if (!$property->getAttributes(IsOptional::class)) {
                throw new PropertyException($property, 'NOT_EMPTY');
            }

            return;
        }

        $attributes = $property->getAttributes(IValidationProperty::class, ReflectionAttribute::IS_INSTANCEOF);
        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            $instance->validateProperty($property, $object);
        }
    }

    /**
     * Set language
     *
     * @param string $lang Language file name (without .php)
     */
    public static function setLang(string $lang): void
    {
        static::$lang = $lang;
    }

    /**
     * Set language locale directory
     *
     * @param string $langDir language directory path
     */
    public static function setLangDir(string $langDir): void
    {
        static::$langDir = $langDir;
    }
}
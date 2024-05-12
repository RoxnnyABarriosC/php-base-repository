<?php

namespace Shared\App\Validator;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Shared\App\Validator\Exceptions\LocaleException;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Exceptions\ValidationErrorException;
use Shared\App\Validator\Interfaces\IValidateConstrain;
use Shared\App\Validator\Interfaces\IValidateConstraint;
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
     * @throws PropertyException
     */
    public static function validate(array $object, $target): bool
    {
        $reflection = new ReflectionClass($target);

        $properties = $reflection->getProperties();

        $errors = [];

        foreach ($properties as $property) {
            $constraint = self::validateProperty($property, $object);

            if (empty($constraint)) continue;

            $errors[] = $constraint;
        }

        if(count($errors)) {
            throw new ValidationErrorException($errors);
        }

        return true;
    }

    protected static function validateProperty(ReflectionProperty $property, object $object): array
    {
        $attributes = $property->getAttributes(IValidateConstraint::class, ReflectionAttribute::IS_INSTANCEOF);

        $constraint = [
            'property' => $property->getName(),
            'value' => $object->{$property->getName()},
            'constraint' => [],
        ];

        foreach ($attributes as $attribute) {
            $attributePathName = explode('\\', $attribute->getName());
            $annotationsName = end($attributePathName);

            $instance = $attribute->newInstance();
            $isValid = $instance->validate($property, $object);

            if (!$isValid) {
                $constraint['constraint'][] = [
                    'name' => $annotationsName,
                    'message' => $instance->defaultMessage($property, $object),
                    'langKey' => null,
                ];
            }
        }

        return $constraint['constraint'] ? $constraint : [];

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
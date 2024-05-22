<?php

namespace Shared\App\Validator;

require_once __DIR__ . '/../../Utils/Transformers.php';

use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Shared\App\Validator\Annotations\Common\Type;
use Shared\App\Validator\Interfaces\ITransformValue;
use Shared\Utils\_Object;


class Transform
{

    private static bool $whiteList = false;


    public static function build(
        bool $whiteList = false,
    ): void
    {
        self::$whiteList = $whiteList;
    }


    public static function transform(object $data, mixed $target): object
    {
        $reflection = new ReflectionClass($target);
        $properties = $reflection->getProperties();

        $object = new $target();
        $targetInstance = new $target();

        _Object::assign($object, $data);

        self::validateObject(
            properties: $properties,
            object: $object,
            target: $targetInstance
        );

        return $targetInstance;
    }

    private static function validateObject(array $properties, object $object, object &$target): void
    {
        $annotatedProperties = self::getAnnotatedProperties($properties);

        foreach ($annotatedProperties as $property) {
            $object->$property = $object->$property ?? null;
        }

        foreach ($object as $key => $value) {
            $property = self::getProperty($properties, $key);
            $hasAnnotations = self::hasAnnotations($property);

            if (self::$whiteList && !$hasAnnotations) {
                continue;
            }

            if (!self::$whiteList && !$property) {
                $target->$key = $value;
                continue;
            }

            if (self::hasNestedAnnotations($property)) {
                self::nestedProperty(
                    property: $property,
                    object: $object,
                    key: $key,
                    value: $value,
                    target: $target
                );
                continue;
            }

            self::property(
                property: $property,
                object: $object,
                key: $key,
                value: $value,
                target: $target
            );
        }
    }


    private static function property(ReflectionProperty $property, object $object, string $key, mixed $value, object &$target): void
    {
        $target->$key = self::transformProperty($property, $value, $object);
    }


    private static function nestedProperty(ReflectionProperty $property, object $object, string $key, mixed $value, object &$target): void
    {

        $target->$key = self::validateNestedProperty($property, $value, $object);
    }


    private static function transformProperty(ReflectionProperty $property, mixed $value, object $object): mixed
    {
        $attributes = $property->getAttributes(ITransformValue::class, ReflectionAttribute::IS_INSTANCEOF);

        return array_reduce($attributes, function ($carry, $attribute) use ($property, $object) {
            $instance = $attribute->newInstance();
            return $instance->transform($property, $object, $carry);
        }, $value);
    }


    private static function validateNestedProperty(ReflectionProperty $property, mixed $value, object $object)
    {
        $target = $property->getAttributes(Type::class)[0]->newInstance()->target;
        $reflection = new ReflectionClass($target);
        $properties = $reflection->getProperties();

        $value = self::transformProperty($property, $value, $object);

        if (!is_object($value) && !is_array($value)) {
            return $value;
        };

        if (is_array($value)) {

            $newValue = [];


            foreach ($value as $index => $item) {

                $nestedTargetInstance = new $target();
                $nestedObject = new $target();

                if (!is_object($item)) {
                    continue;
                }

                _Object::assign($nestedObject, $item);

                self::validateObject(
                    properties: $properties,
                    object: $nestedObject,
                    target: $nestedTargetInstance,
                );

                $newValue[] = $nestedTargetInstance;

            }

            return $newValue;
        }

        $nestedTargetInstance = new $target();
        $nestedObject = new $target();

        _Object::assign($nestedObject, $value);

        self::validateObject(
            properties: $properties,
            object: $nestedObject,
            target: $nestedTargetInstance,
        );

        return $nestedTargetInstance;
    }

    /**
     * Get a property from an array of properties.
     *
     * @param ReflectionProperty[] $properties The properties to validate against.
     * @param string $key The key of the property to validate.
     * @return ReflectionProperty|bool The property if found, false otherwise.
     */
    private static function getProperty(array $properties, string $key): ReflectionProperty|bool
    {
        $result = array_filter($properties, fn($item) => $item->getName() === $key);
        return reset($result);
    }

    /**
     * Check if a property has annotations.
     *
     * @param ReflectionProperty|bool $property The property to check.
     * @return bool True if the property has annotations, false otherwise.
     */
    private static function hasAnnotations(ReflectionProperty|bool $property): bool
    {
        if (!is_bool($property)) {
            $transforms = (bool)$property->getAttributes(ITransformValue::class, ReflectionAttribute::IS_INSTANCEOF);
            return $transforms || self::hasNestedAnnotations($property);
        }
        return false;
    }

    /**
     * Check if a property has nested annotations.
     *
     * @param ReflectionProperty $property The property to check.
     * @return bool True if the property has nested annotations, false otherwise.
     */
    private static function hasNestedAnnotations(ReflectionProperty $property): bool
    {
        if (!is_bool($property)) {
            return (bool)$property->getAttributes(Type::class);
        }
        return false;
    }

    /**
     * Get the annotated properties of an object.
     *
     * @param ReflectionProperty[] $properties The properties to validate against.
     * @return string[] The annotated properties.
     */
    private static function getAnnotatedProperties(array $properties): array
    {
        return array_reduce($properties, function ($carry, $property) {
            if (self::hasAnnotations($property)) {
                $carry[] = $property->getName();
            }
            return $carry;
        }, []);
    }
}
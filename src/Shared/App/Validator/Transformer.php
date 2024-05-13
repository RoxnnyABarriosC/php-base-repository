<?php

namespace Shared\App\Validator;

use Exception;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Validator\Annotations\Allow;
use Shared\App\Validator\Annotations\IsOptional;
use Shared\App\Validator\Annotations\Type;
use Shared\App\Validator\Annotations\ValidateNested;
use Shared\App\Validator\Exceptions\ValidationErrorException;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Object;
use stdClass;

class Transformer
{
    private static bool $whiteList = false;
    private static bool $forbidNonWhitelisted = false;
    private static HttpStatus $errorHttpStatusCode = HttpStatus::BAD_REQUEST;


    /*
     * The array of errors that occurred during validation.
     *
     * @var ConstraintErrorModel[] $errors The array of errors that occurred during validation.
     * */
    private static array $errors = [];

    public static function build(
        bool       $whiteList = false,
        bool       $forbidNonWhitelisted = false,
        HttpStatus $errorHttpStatusCode = HttpStatus::BAD_REQUEST,
    ): void
    {
        self::$whiteList = $whiteList;
        self::$forbidNonWhitelisted = $forbidNonWhitelisted;
        self::$errorHttpStatusCode = $errorHttpStatusCode;
    }

    /**
     * @throws \ReflectionException
     * @throws ValidationErrorException
     * @throws Exception
     */
    public static function validate(object $data, $target): object
    {
        $reflection = new ReflectionClass($target);
        $properties = $reflection->getProperties();

        $object = new $target();
        $targetInstance = new $target();

        _Object::assign($object, $data);

        self::validateObject(
            properties: $properties,
            object: $object,
            target: $targetInstance,
            constraint: self::$errors,
            children: self::$errors
        );

        if (count(self::$errors)) {
            throw new ValidationErrorException(self::$errors);
        }

        return $targetInstance;
    }

    /**
     * @param ReflectionProperty[] $properties The constraints that should be applied to the value.
     * @param ConstraintErrorModel[] $constraint The constraints that should be applied to the value.
     * @param ConstraintErrorModel[] $children The constraints that should be applied to the value.
     */
    private static function validateObject(array $properties, object $object, object &$target, array &$constraint, array &$children): void
    {

        foreach ($object as $key => $value) {
            $property = self::getProperty($properties, $key);
            $hasAnnotations = self::hasAnnotations($property);

            if (self::$whiteList && !$hasAnnotations) {
                self::forbidNonWhitelisted($object, $key, $constraint, true);
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
                    target: $target,
                    constraint: $children
                );
                continue;
            }

            self::property(
                property: $property,
                object: $object,
                key: $key,
                value: $value,
                target: $target,
                constraint: $children
            );
        }
    }

    /**
     *
     * @param ConstraintErrorModel[]  $constraint The constraints that should be applied to the value.
     */
    private static function property(ReflectionProperty $property, object $object, string $key, mixed $value, object &$target, array &$constraint): void
    {
        $_constraint = self::validateProperty($property, $value, $object);

        if (!empty($_constraint->constraint->constraint)) {
            $constraint[] = $_constraint->constraint;
        }

        $target->$key = $_constraint->value;
    }

    private static function nestedProperty(ReflectionProperty $property, object $object, string $key, mixed $value, object &$target, array|object &$constraint): void
    {
        $_constraint = self::validateNestedProperty($property, $value, $object);

        if (!empty($_constraint->constraint->constraint) ||
            !empty($_constraint->constraint->children)
        ) {
            $constraint[] = $_constraint->constraint;
        }

        $target->$key = $_constraint->value;
    }

    private static function validateProperty(ReflectionProperty $property, mixed $value, object $object): MapConstraint
    {
        $attributes = $property->getAttributes(IValidateConstraint::class, ReflectionAttribute::IS_INSTANCEOF);

        $constraint = new ConstraintErrorModel(
            property: $property->getName(),
            value: $value,
            constraint: [],
            children: []
        );

        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            $pathName = explode('\\', $attribute->getName());

            if (!$instance->validate($property, $object)) {
                $constraint->constraint[] = new  ConstraintModel(
                    name: end($pathName),
                    message: $instance->defaultMessage($property, $object),
                    langKey: null
                );
            }
        }

        return new MapConstraint(
            value: $value,
            constraint: $constraint
        );

    }

    private static function validateNestedProperty(ReflectionProperty $property, mixed $value, object $object): MapConstraint
    {

        $target = $property->getAttributes(Type::class)[0]->newInstance()->target;
        $reflection = new ReflectionClass($target);
        $properties = $reflection->getProperties();
        $nestedTargetInstance = new $target();
        $nestedObject = new $target();

        $propertyConstraint = self::validateProperty($property, $value, $object);
        $constraint = $propertyConstraint->constraint;

        $data = new MapConstraint(
            value: is_object($value) ? $nestedTargetInstance : $value,
            constraint: $constraint
        );

        if (!is_object($value)) {
            return $data;
        };

        _Object::assign($nestedObject, $value);

        $constraint->children = [];

        self::validateObject(
            properties: $properties,
            object: $nestedObject,
            target: $nestedTargetInstance,
            constraint: $constraint->children,
            children: $constraint->children
        );

        return $data;
    }


    /**
     *
     * @param ConstraintErrorModel[] $constraint The constraints that should be applied to the value.
     */
    private static function forbidNonWhitelisted(object $object, string $property, array &$constraint): void
    {
        if (self::$forbidNonWhitelisted) {
            $constraint[] = new  ConstraintErrorModel(
                property: $property,
                value: $object->$property,
                constraint: [
                    new ConstraintModel(
                        name: 'forbidden',
                        message: 'Property not allowed',
                        langKey: null
                    )
                ],
                children: []
            );
        }
    }


    /**
     * @param ReflectionProperty[] $properties The constraints that should be applied to the value.
     */
    private static function getProperty(array $properties, string $key): ReflectionProperty|bool
    {
        $result = array_filter($properties, fn($item) => $item->getName() === $key);
        return reset($result);
    }

    private static function hasAnnotations(ReflectionProperty $property): bool
    {
        if (!is_bool($property)) {
            $allow = (bool)$property->getAttributes(Allow::class);
            $isOptional = (bool)$property->getAttributes(IsOptional::class);
            $validators = (bool)$property->getAttributes(IValidateConstraint::class, ReflectionAttribute::IS_INSTANCEOF);
            return $allow || $isOptional || $validators || self::hasNestedAnnotations($property);
        }
        return false;
    }

    private static function hasNestedAnnotations(ReflectionProperty $property): bool
    {
        if (!is_bool($property)) {
            return (bool)$property->getAttributes(Type::class) && (bool)$property->getAttributes(ValidateNested::class);
        }
        return false;
    }
}
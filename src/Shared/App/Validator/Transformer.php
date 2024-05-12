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
use stdClass;

class Transformer
{
    private static bool $whiteList = false;
    private static bool $forbidNonWhitelisted = false;
    private static bool $forbidUnknownValues = false;
    private static HttpStatus $errorHttpStatusCode = HttpStatus::BAD_REQUEST;

    private static array $errors = [];

    public static function build(
        bool       $whiteList = false,
        bool       $forbidNonWhitelisted = false,
        bool       $forbidUnknownValues = false,
        HttpStatus $errorHttpStatusCode = HttpStatus::BAD_REQUEST,
    ): void
    {
        self::$whiteList = $whiteList;
        self::$forbidNonWhitelisted = $forbidNonWhitelisted;
        self::$forbidUnknownValues = $forbidUnknownValues;
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

        foreach ($data as $property => $value) {
            $object->$property = $value;
        }

        foreach ($object as $key => $value) {
            $property = self::getProperty($properties, $key);
            $hasAnnotations = self::hasAnnotations($property);

            if (self::$whiteList && !$hasAnnotations) {
                if (self::$forbidNonWhitelisted) {
                    throw new Exception("Property $key not found", self::$errorHttpStatusCode->value);
                }
                unset($object->$key);
                continue;
            }

            if (!self::$whiteList && !$property) {
                $targetInstance->$key = $value;
                continue;
            }

            if (self::hasNestedAnnotations($property)) {
                $nestedPropertyConstraint = self::validateNestedProperty($property, $value, $object);

//                echo json_encode($nestedPropertyConstraint, JSON_PRETTY_PRINT) . PHP_EOL;

                if (!empty($nestedPropertyConstraint->constraint->constraint) ||
                    !empty($nestedPropertyConstraint->constraint->children)
                ) {
                    self::$errors[] = $nestedPropertyConstraint->constraint;
                }

                $targetInstance->$key = $nestedPropertyConstraint->value;
                continue;
            }

            $propertyConstraint = self::validateProperty($property, $value, $object);

            if (!empty($propertyConstraint->constraint->constraint)) {
                self::$errors[] = $propertyConstraint->constraint;
            }

            $targetInstance->$key = $propertyConstraint->value;
        }

        return $targetInstance;
    }

    /**
     * @throws \ReflectionException
     * @throws ValidationErrorException
     */
    public static function validate_(object $data, $target): object
    {
        $obj = self::validate($data, $target);

        if (count(self::$errors)) {
            throw new ValidationErrorException(self::$errors);
        }

        return $obj;
    }

    private static function getProperty($properties, $key): ReflectionProperty|bool
    {
        $result = array_filter($properties, fn($item) => $item->getName() === $key);
        return reset($result);
    }

    private static function hasAnnotations($property): bool
    {
        if (!is_bool($property)) {
            $allow = (bool)$property->getAttributes(Allow::class);
            $isOptional = (bool)$property->getAttributes(IsOptional::class);
            $validators = (bool)$property->getAttributes(IValidateConstraint::class, ReflectionAttribute::IS_INSTANCEOF);
            return $allow || $isOptional || $validators || self::hasNestedAnnotations($property);
        }
        return false;
    }

    private static function hasNestedAnnotations($property): bool
    {
        if (!is_bool($property)) {
            return (bool)$property->getAttributes(Type::class) && (bool)$property->getAttributes(ValidateNested::class);
        }
        return false;
    }


    private static function validateProperty(ReflectionProperty $property, mixed $value, object $object): stdClass
    {
        $attributes = $property->getAttributes(IValidateConstraint::class, ReflectionAttribute::IS_INSTANCEOF);

        $constraint = new stdClass();
        $constraint->property = $property->getName();
        $constraint->value = $value;
        $constraint->constraint = [];

        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            $pathName = explode('\\', $attribute->getName());

            if (!$instance->validate($property, $object)) {
                $constraint->constraint[] = [
                    'name' => end($pathName),
                    'message' => $instance->defaultMessage($property, $object),
                    'langKey' => null,
                ];
            }
        }

        $data = new stdClass();

        $data->constraint = $constraint;
        $data->value = $value;

        return $data;
    }

    /**
     * //     * @throws \ReflectionException
     * //     * @throws ValidationErrorException
     * //     */
    private static function validateNestedProperty(ReflectionProperty $property, mixed $value, object $object): object
    {

        $target = $property->getAttributes(Type::class)[0]->newInstance()->target;
        $reflection = new ReflectionClass($target);
        $properties = $reflection->getProperties();
        $nestedTargetInstance = new $target();
        $nestedObject = new $target();

        $propertyConstraint = self::validateProperty($property, $value, $object);
        $constraint = $propertyConstraint->constraint;

        if (is_object($value)) {

            foreach ($value as $property_ => $value_) {
                $nestedObject->$property_ = $value_;
            }

            $constraint->children = [];

            foreach ($nestedObject as $key => $nestedValue) {
                $property = self::getProperty($properties, $key);
                $hasAnnotations = self::hasAnnotations($property);

                if (self::$whiteList && !$hasAnnotations) {
                    if (self::$forbidNonWhitelisted) {
                        throw new Exception("Property $key not found", self::$errorHttpStatusCode->value);
                    }
                    unset($nestedObject->$key);
                    continue;
                }

                if (!self::$whiteList && !$property) {
                    $nestedTargetInstance->$key = $nestedObject->$key;
                    continue;
                }

                if (self::hasNestedAnnotations($property)) {
                    $nestedTargetInstance->$key = self::validateNestedProperty($property, $nestedObject->$key, $nestedObject);
                    continue;
                }

                $propertyConstraint = self::validateProperty($property, $nestedObject->$key, $nestedObject);

                if (!empty($propertyConstraint->constraint->constraint)) {
                    $constraint->children[] = $propertyConstraint->constraint;
                }

                $nestedTargetInstance->$key = $propertyConstraint->value;
            }

        }

        $data = new stdClass();

        $data->constraint = $constraint;
        $data->value = is_object($value) ? $nestedTargetInstance : $value;

        return $data;
    }
}
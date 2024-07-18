<?php

namespace Shared\App\Validator;

require_once __DIR__ . '/../../Utils/Transformers.php';

use Exception;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Validator\Annotations\Common\Allow;
use Shared\App\Validator\Annotations\Common\IsOptional;
use Shared\App\Validator\Annotations\Common\Type;
use Shared\App\Validator\Annotations\Common\ValidateNested;
use Shared\App\Validator\Exceptions\ValidationErrorException;
use Shared\App\Validator\Interfaces\IValidateConstraint;
use Shared\Utils\_Object;
use stdClass;

/**
 * Class Validator
 *
 * This class is responsible for validating objects based on their properties and annotations.
 * It uses reflection to inspect the properties and annotations of the object, and applies the appropriate validation rules.
 */
class Validator
{
    /**
     * @var bool $whiteList Whether to only allow properties that are explicitly whitelisted.
     */
    private static bool $whiteList = false;

    /**
     * @var bool $forbidNonWhitelisted Whether to forbid properties that are not whitelisted.
     */
    private static bool $forbidNonWhitelisted = false;

    /**
     * @var HttpStatus $errorHttpStatusCode The HTTP status code to use for validation errors.
     */
    private static HttpStatus $errorHttpStatusCode = HttpStatus::BAD_REQUEST;

    private static $mapError;

    /**
     * @var ConstraintErrorModel[] $errors The array of errors that occurred during validation.
     */
    private static array $errors = [];

    /**
     * Build the Transformer with the given configuration.
     *
     * @param bool $whiteList Whether to only allow properties that are explicitly whitelisted.
     * @param bool $forbidNonWhitelisted Whether to forbid properties that are not whitelisted.
     * @param HttpStatus $errorHttpStatusCode The HTTP status code to use for validation errors.
     * @param callable|null $mapError The callback function to use for mapping errors.
     */
    public static function build(
        bool       $whiteList = false,
        bool       $forbidNonWhitelisted = false,
        HttpStatus $errorHttpStatusCode = HttpStatus::BAD_REQUEST,
        callable   $mapError = null
    ): void
    {
        self::$whiteList = $whiteList;
        self::$forbidNonWhitelisted = $forbidNonWhitelisted;
        self::$errorHttpStatusCode = $errorHttpStatusCode;
        self::$mapError = $mapError;
    }

    /**
     * Validate the given data object against the target class.
     *
     * @param object $data The data object to validate.
     * @param mixed $target The target class to validate against.
     * @return object The validated object.
     * @throws ReflectionException If the target class does not exist.
     * @throws ValidationErrorException If the data object fails validation.
     * @throws Exception If an unexpected error occurs.
     */
    public static function validate(object $data, mixed $target): object
    {
        $reflection = new ReflectionClass($target);
        $properties = $reflection->getProperties();

        $object = new $target();
        $targetInstance = new $target();

        _Object::assign($object, $data);

        $object = Transform::transform($object, $target);

        self::validateObject(
            properties: $properties,
            object: $object,
            target: $targetInstance,
            constraint: self::$errors,
            children: self::$errors
        );

        if (count(self::$errors)) {

            $errors = self::$errors;

            if (self::$mapError) {
                $mapError = self::$mapError;
                $errors = $mapError(self::$errors);
            }

            throw new ValidationErrorException($errors, self::$errorHttpStatusCode);
        }

        return $targetInstance;
    }

    /**
     * Validate the given object against the given properties and constraints.
     *
     * @param ReflectionProperty[] $properties The properties to validate against.
     * @param object $object The object to validate.
     * @param object $target The target object to validate against.
     * @param ConstraintErrorModel[] $constraint The constraints to validate against.
     * @param ConstraintErrorModel[] $children The child constraints to validate against.
     * @throws ReflectionException
     */
    private static function validateObject(array $properties, object $object, object &$target, array &$constraint, array &$children): void
    {

        $annotatedProperties = self::getAnnotatedProperties($properties);

        foreach ($annotatedProperties as $property) {
            $object->$property = $object->$property ?? null;
        }

        foreach ($object as $key => $value) {
            $property = self::getProperty($properties, $key);
            $hasAnnotations = self::hasAnnotations($property);

            if (self::$whiteList && !$hasAnnotations) {
                self::forbidNonWhitelisted($object, $key, $constraint);
                continue;
            }

            if (!self::$whiteList && !$property) {
                $target->$key = $value;
                continue;
            }

            if (!self::hasNestedAnnotations($property)) {
                self::property(
                    property: $property,
                    object: $object,
                    key: $key,
                    value: $value,
                    target: $target,
                    constraint: $children
                );
                continue;
            }

            self::nestedProperty(
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
     * Validate a property of the given object.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object to validate.
     * @param string $key The key of the property to validate.
     * @param mixed $value The value of the property to validate.
     * @param object $target The target object to validate against.
     * @param ConstraintErrorModel[] $constraint The constraints to validate against.
     */
    private static function property(ReflectionProperty $property, object $object, string $key, mixed $value, object &$target, array &$constraint): void
    {
        $_constraint = self::validateProperty($property, $value, $object);

        self::setConstraint($_constraint->constraint, $constraint);

        $target->$key = $_constraint->value;
    }

    /**
     * Validate a nested property of the given object.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param object $object The object to validate.
     * @param string $key The key of the property to validate.
     * @param mixed $value The value of the property to validate.
     * @param object $target The target object to validate against.
     * @param ConstraintErrorModel[] $constraint The constraints to validate against.
     * @throws ReflectionException
     */
    private static function nestedProperty(ReflectionProperty $property, object $object, string $key, mixed $value, object &$target, array &$constraint): void
    {
        $_constraint = self::validateNestedProperty($property, $value, $object);

        self::setConstraint($_constraint->constraint, $constraint);

        $target->$key = $_constraint->value;
    }

    /**
     * Validate a property of the given object.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param mixed $value The value of the property to validate.
     * @param object $object The object that contains the property to validate.
     * @return MapConstraint The validated value and any validation errors.
     */
    private static function validateProperty(ReflectionProperty $property, mixed $value, object $object): MapConstraint
    {
        $data = new MapConstraint(
            value: $value,
            constraint: null
        );

        if ($property->getAttributes(IsOptional::class) && !$value) {
            return $data;
        }

        $attributes = $property->getAttributes(IValidateConstraint::class, ReflectionAttribute::IS_INSTANCEOF);

        $constraint = new ConstraintErrorModel(
            property: $property->getName(),
            value: $value,
            constraints: (object)[],
            children: []
        );

        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            $pathName = explode('\\', $attribute->getName());

            if (!$instance->validate($property, $object)) {
                $constraint->constraints->{end($pathName)} = $instance->defaultMessage($property, $object);
            }
        }

        $data->constraint = $constraint;

        return $data;
    }

    /**
     * Validates a nested property of an object.
     *
     * This method is used to validate a property of an object that is itself an object (nested object).
     * It uses reflection to inspect the property and its annotations, and applies the appropriate validation rules.
     * If the property is not an object, it simply returns the value and constraint without further validation.
     * If the property is an object, it creates a new instance of the target class, assigns the value to it,
     * and then validates this new object against the target class.
     * The result is a MapConstraint object that contains the validated value and any validation errors.
     *
     * @param ReflectionProperty $property The property to validate.
     * @param mixed $value The value of the property to validate.
     * @param object $object The object that contains the property to validate.
     * @return MapConstraint The validated value and any validation errors.
     * @throws ReflectionException
     */
    private static function validateNestedProperty(ReflectionProperty $property, mixed $value, object $object): MapConstraint
    {
        $target = $property->getAttributes(Type::class)[0]->newInstance()->target;
        $each = $property->getAttributes(ValidateNested::class)[0]->newInstance()->each;
        $reflection = new ReflectionClass($target);
        $properties = $reflection->getProperties();
        $nestedTargetInstance = new $target();
        $nestedObject = new $target();

        $propertyConstraint = self::validateProperty($property, $value, $object);
        $constraint = $propertyConstraint->constraint;
        $constraint->children = [];

        $data = new MapConstraint(
            value: is_object($value) ? $nestedTargetInstance : $value,
            constraint: $constraint
        );

        if (!is_object($value) && !is_array($value)) {
            return $data;
        };

        if ($each && is_array($value)) {
            foreach ($value as $index => $item) {

                $_constraint_ = new ConstraintErrorModel(
                    property: (string)$index,
                    value: $item,
                    constraints: (object)[],
                    children: []
                );

                if (!is_object($item)) {

                    $_constraint_->constraints->nestedValidation = 'each value in nested property ' . $property->getName() . ' must be either object or array';
                    $constraint->children[] = $_constraint_;
                    continue;
                }

                _Object::assign($nestedObject, $item);

                self::validateObject(
                    properties: $properties,
                    object: $nestedObject,
                    target: $nestedTargetInstance,
                    constraint: $_constraint_->children,
                    children: $_constraint_->children
                );

                self::setConstraint($_constraint_, $constraint->children);

            }
        }

        if (!$each && is_object($value)) {
            _Object::assign($nestedObject, $value);

            self::validateObject(
                properties: $properties,
                object: $nestedObject,
                target: $nestedTargetInstance,
                constraint: $constraint->children,
                children: $constraint->children
            );
        }

        return $data;
    }

    /**
     * Forbid non-whitelisted properties.
     *
     * @param object $object The object to validate.
     * @param string $property The property to validate.
     * @param ConstraintErrorModel[] $constraint The constraints to validate against.
     */
    private static function forbidNonWhitelisted(object $object, string $property, array &$constraint): void
    {
        if (self::$forbidNonWhitelisted) {

            $constraints = new stdClass();

            $constraints->WhitelistValidation = 'This property is not allowed';

            $constraint[] = new ConstraintErrorModel(
                property: $property,
                value: $object->$property,
                constraints: $constraints,
                children: []
            );
        }
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
            $allow = (bool)$property->getAttributes(Allow::class);
            $isOptional = (bool)$property->getAttributes(IsOptional::class);
            $validators = (bool)$property->getAttributes(IValidateConstraint::class, ReflectionAttribute::IS_INSTANCEOF);
            return $allow || $isOptional || $validators || self::hasNestedAnnotations($property);
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
            return (bool)$property->getAttributes(Type::class) && (bool)$property->getAttributes(ValidateNested::class);
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

    /**
     * This method is used to add a ConstraintErrorModel to a target array if it contains any constraints or children.
     * It checks if the constraints property of the ConstraintErrorModel is not empty or if it has any children.
     * If either of these conditions is true, it adds the ConstraintErrorModel to the target array.
     *
     * @param ?ConstraintErrorModel $constraint The ConstraintErrorModel to check and potentially add to the target array.
     * @param ConstraintErrorModel[] $target The target array to which the ConstraintErrorModel may be added.
     */
    private static function setConstraint(?ConstraintErrorModel $constraint, array &$target): void
    {
        if (!empty((array)$constraint?->constraints) || !empty($constraint?->children)) {
            $target[] = $constraint;
        }
    }
}
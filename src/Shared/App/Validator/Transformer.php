<?php

namespace Shared\App\Validator;


use Exception;
use ReflectionAttribute;
use ReflectionClass;
use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Validator\Annotations\Allow;
use Shared\App\Validator\Annotations\IsOptional;
use Shared\App\Validator\Exceptions\ValidationErrorException;
use Shared\App\Validator\Interfaces\IValidateConstraint;


// TODO: cambiar nombre a validator
class Transformer
{

    /*
     * If set to true validator will strip validated object of any properties that do not have any decorators.
     * Tip: if no other decorator is suitable for your property use @Allow decorator.
     *
     *
     * Si se establece en verdadero, el validador eliminará el objeto validado de cualquier propiedad que no tenga decoradores.
      * Consejo: si ningún otro decorador es adecuado para su propiedad, utilice @Allow decorador.
     * */
    private static bool $whiteList = false;
    private static bool $transform = false;

    /*
     * If set to true, instead of stripping non-whitelisted properties validator will throw an error.
     *
     * Si se establece en verdadero, en lugar de eliminar las propiedades no incluidas en la lista blanca, el validador arrojará un error.
     * */
    private static bool $forbidNonWhitelisted = false;

    /*
     * Settings true will cause fail validation of unknown objects.
     *
     * La configuración verdadera provocará una falla en la validación de objetos desconocidos.
     * */
    private static bool $forbidUnknownValues = false;
    private static HttpStatus $errorHttpStatusCode = HttpStatus::BAD_REQUEST;


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
     * @throws \Exception
     */
    public static function validate(array $data, $target): object
    {
        $reflection = new ReflectionClass($target);
        $properties = $reflection->getProperties();

        $targetInstance = new $target();
        $object = json_decode(json_encode($data));

        $errors = [];

        foreach ($object as $key => $value) {

            $result = array_filter($properties, fn($item) => $item->getName() === $key);
            $property = reset($result);
            $hasAnnotations = false;

            if (!is_bool($property)) {
                $allow = (bool)$property->getAttributes(Allow::class);
                $isOptional = (bool)$property->getAttributes(IsOptional::class);
                $validators = (bool)$property->getAttributes(IValidateConstraint::class, ReflectionAttribute::IS_INSTANCEOF);
                $hasAnnotations = $allow || $isOptional || $validators;
            }

            if (self::$whiteList && !$hasAnnotations && self::$forbidNonWhitelisted) {
                throw new Exception("Property $key not found", self::$errorHttpStatusCode->value);
            }

            if (self::$whiteList && !$hasAnnotations) {
                unset($data[$key]);
                continue;
            }

            if (is_bool($property)) {
                $targetInstance->$key = $value;
                continue;
            }

            $attributes = $property->getAttributes(IValidateConstraint::class, ReflectionAttribute::IS_INSTANCEOF);

            $constraint = [
                'property' => $property->getName(),
                'value' => $value,
                'constraint' => [],
            ];

            $targetInstance->$key = $value;

            foreach ($attributes as $attribute) {
                $attributePathName = explode('\\', $attribute->getName());
                $annotationsName = end($attributePathName);

                $instance = $attribute->newInstance();
                $isValid = $instance->validate($property, $targetInstance);

                if (!$isValid) {
                    $constraint['constraint'][] = [
                        'name' => $annotationsName,
                        'message' => $instance->defaultMessage($property, $object),
                        'langKey' => null,
                    ];
                }
            }

            $constraint['constraint'] ? $errors[] = $constraint : null;
        }

        if (count($errors)) {
            throw new ValidationErrorException($errors);
        }

        return $targetInstance;
    }
}
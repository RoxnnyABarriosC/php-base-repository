<?php

namespace Shared\App\Validator\Annotations;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidationProperty;
use Shared\App\Validator\Validator;
use Throwable;

/**
 * Validate the value is instance of object and execute validation
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ValidateNested implements IValidationProperty
{
    /** @param string|null $type Class type namespace (example: MyClass::class) * */
    public function __construct(
        private string|null $type = null,
    )
    {
    }

    /**
     * @throws PropertyException
     */
    public function validateProperty(ReflectionProperty $property, object $object): void
    {
        $value = $property->getValue($object);
        if (isset($this->type)) {
            if (!$value instanceof $this->type) {
                throw new PropertyException($property, 'VALIDATE_CLASS_INVALID');
            }
        }

        try {
            Validator::validate($value);
        } catch (Throwable $th) {
            $msg = $th->getMessage();

            throw new PropertyException($property, 'VALIDATE_CLASS_INVALID_NESTED', $msg);
        }
    }
}
<?php

namespace Shared\App\Validator\Annotations;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidationProperty;
use Shared\App\Validator\Validator;
use Throwable;

use function is_array;

/**
 * Validate nested array of class
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ValidateNestedArray implements IValidationProperty
{
    /** @param string|null $type Class type namespace (example: MyClass::class) **/
    public function __construct(
        private string|null $type = null,
    ) {
    }

    /**
     * @throws PropertyException
     */
    public function validateProperty(ReflectionProperty $property, object $object): void
    {
        $value = $property->getValue($object);
        if (! is_array($value)) {
            throw new PropertyException($property, 'VALIDATE_ARRAY_CLASS_INVALID');
        }

        $idx = 0;
        foreach ($value as $item) {
            if (isset($this->type)) {
                if (! $item instanceof $this->type) {
                    throw new PropertyException($property, 'VALIDATE_ARRAY_CLASS_INVALID');
                }
            }

            try {
                Validator::validate($item);
            } catch (Throwable $th) {
                $msg = $th->getMessage();

                throw new PropertyException($property, 'VALIDATE_ARRAY_CLASS_INDEX_INVALID', $idx, $msg);
            }

            $idx++;
        }
    }
}
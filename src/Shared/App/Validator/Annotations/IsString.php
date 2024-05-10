<?php

namespace Shared\App\Validator\Annotations;

use Attribute;
use ReflectionProperty;
use Shared\App\Validator\Exceptions\PropertyException;
use Shared\App\Validator\Interfaces\IValidationProperty;
use function strlen;

/**
 * Validate string length
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsString implements IValidationProperty
{
    /**
     * @param int|null $length Fixed length
     * @param int|null $minLength Minimum string length
     * @param int|null $maxLength Maximum string length
     */
    public function __construct(
        private int|null $length = null,
        private int|null $minLength = null,
        private int|null $maxLength = null,
    )
    {
    }

    /**
     * @throws PropertyException
     */
    public function validateProperty(ReflectionProperty $property, object $object): void
    {
        $value = $property->getValue($object);

        if (isset($this->length) && strlen((string)$value) !== $this->length) {
            throw new PropertyException($property, 'STRING_INVALID_LENGTH', $this->length);
        }

        if (isset($this->minLength) && strlen((string)$value) < $this->minLength) {
            throw new PropertyException($property, 'STRING_INVALID_MIN_LENGTH', $this->minLength);
        }

        if (isset($this->maxLength) && strlen((string)$value) > $this->maxLength) {
            throw new PropertyException($property, 'STRING_INVALID_MAX_LENGTH', $this->maxLength);
        }
    }
}
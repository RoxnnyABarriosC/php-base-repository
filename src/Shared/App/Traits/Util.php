<?php

namespace Shared\App\Traits;

use InvalidArgumentException;

/**
 * Trait Magic
 *
 * This trait provides magic methods for setting, getting, checking and unsetting properties.
 * It also provides magic methods for calling and statically calling methods.
 * It includes methods for converting the object to a string and an array.
 */
trait  Util
{
    public function get(): ?array
    {
        $properties = array_keys(get_class_vars(get_class($this)));
        $data = [];

        foreach ($properties as $property) {
            if ($this->$property) {
                $data[$property] = $this->$property;
            }
        }

        if (empty($data)) {
            return null;
        }

        return $data;
    }

    public function has(string $property): bool
    {
        return !empty($this->$property);
    }

    public function build(...$args): void
    {
        foreach ($args as $arg) {
            foreach ($arg as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}
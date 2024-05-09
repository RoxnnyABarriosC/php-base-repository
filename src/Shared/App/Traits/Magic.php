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
trait Magic
{
    /**
     * Magic set method.
     *
     * @param string $name The name of the property to set.
     * @param mixed $value The value to set the property to.
     *
     * @throws InvalidArgumentException If the property does not exist.
     */
    public function __set(string $name, mixed $value): void
    {
        $this->exist($name);
        $this->$name = $value;
    }

    /**
     * Magic get method.
     *
     * @param string $name The name of the property to get.
     *
     * @return mixed The value of the property.
     *
     * @throws InvalidArgumentException If the property does not exist.
     */
    public function __get(string $name): mixed
    {
        $this->exist($name);
        return $this->$name;
    }

    /**
     * Magic isset method.
     *
     * @param string $name The name of the property to check.
     *
     * @return bool Whether the property is set.
     */
    public function __isset(string $name): bool
    {
        return isset($this->$name);
    }

    /**
     * Magic unset method.
     *
     * @param string $name The name of the property to unset.
     */
    public function __unset(string $name): void
    {
        unset($this->$name);
    }

    /**
     * Checks if a property exists.
     *
     * @param string $name The name of the property to check.
     *
     * @return bool Whether the property exists.
     *
     * @throws InvalidArgumentException If the property does not exist.
     */
    public function exist(string $name, bool $allowThrow = true): bool
    {
        $exist = property_exists($this, $name);

        if (!$exist && $allowThrow) {
            throw new InvalidArgumentException("Property '$name' does not exist in class " . get_class($this));
        }

        return $exist;
    }

    /**
     * Magic call method.
     *
     * @param string $name The name of the method to call.
     * @param array $arguments The arguments to pass to the method.
     *
     * @throws InvalidArgumentException If the method does not exist.
     */
    public function __call(string $name, array $arguments): void
    {
        if (str_starts_with($name, '__')) {
            throw new InvalidArgumentException("Method '$name' does not exist in class " . get_class($this));
        }
    }

    /**
     * Magic static call method.
     *
     * @param string $name The name of the method to call.
     * @param array $arguments The arguments to pass to the method.
     *
     * @throws InvalidArgumentException If the method does not exist.
     */
    public static function __callStatic(string $name, array $arguments): void
    {
        if (str_starts_with($name, '__')) {
            throw new InvalidArgumentException("Method '$name' does not exist in class " . get_called_class());
        }
    }

    /**
     * Converts the object to a string.
     *
     * @return string The object as a JSON string.
     */
    public function __toString(): string
    {
        return json_encode($this);
    }

    /**
     * Converts the object to an array.
     *
     * @return array The object as an associative array.
     */
    public function __toArray(): array
    {
        return json_decode(json_encode($this), true);
    }


}
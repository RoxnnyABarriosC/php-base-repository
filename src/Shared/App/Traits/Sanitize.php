<?php

namespace Shared\App\Traits;


trait Sanitize
{

    protected function sanitize(array $data): static
    {
        $classProperties = array_keys(get_class_vars(get_class($this)));
        $nestedProperties = $this->nestedProperties();
        $transformProperties = $this->transformProperties();

        foreach ($classProperties as $property) {
            if (!array_key_exists($property, $data)) continue;

            $value = $data[$property] ?? $this?->$property ?? null;

            if (array_key_exists($property, $nestedProperties) && !empty($nestedProperties[$property])) {
                $this->$property = $this->sanitizeNestedProperty($value, $nestedProperties[$property]);
                continue;
            }

            if (array_key_exists($property, $transformProperties) && !empty($value) ) {

                $function = $transformProperties[$property];

                if (is_array($function)) {
                    $this->$property = array_reduce($function, fn($carry, $fn) => $fn($carry), $value);
                    continue;
                }

                $this->$property = $function($value);
                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    private function sanitizeNestedProperty(mixed $data, mixed $nested): mixed
    {
        if ($this->isMultiDimensionalArray($data)) {
            return array_map(fn($item) => (new $nested())->sanitize($item), $data);
        }

        return (new $nested())->sanitize($data);
    }

    public function nestedProperties(): array
    {
        return [];
    }

    public static function transformProperties(): array
    {
        return [];
    }

    private function isMultiDimensionalArray(array $array): bool
    {
        return count($array) === count(array_filter($array, 'is_array'));
    }
}
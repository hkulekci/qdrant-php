<?php

namespace Qdrant\Models\Traits;

use InvalidArgumentException;
use ReflectionProperty;

/**
 * Trait ProtectedPropertyAccessor
 *
 * Allows access to protected properties through the magic __get method.
 */
trait StructProtectedPropertyAccessor
{
    /**
     * Magic method to implement generic getter functionality for protected properties.
     *
     * @param string $property The name of the property to get.
     * @return mixed The value of the property.
     * @throws InvalidArgumentException if the property doesn't exist or is not protected.
     */
    public function __get(string $property)
    {
        if (property_exists($this, $property)) {
            $reflection = new ReflectionProperty($this, $property);
            if ($reflection->isProtected()) {
                return $this->$property;
            } else {
                throw new InvalidArgumentException("Access to property '$property' is not allowed");
            }
        }

        throw new InvalidArgumentException("Property '$property' does not exist");
    }
}
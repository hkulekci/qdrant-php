<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter;

use Qdrant\Models\Filter\Condition\ConditionInterface;

class Nested implements ConditionInterface
{
    protected array $container = [];

    public function addFilter(string $key, Filter $filter): Nested
    {
        $this->container[] = [
            'key' => $key,
            'filter' => $filter->toArray()
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->container;
    }
}
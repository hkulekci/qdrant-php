<?php
/**
 * @since     Jun 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter;

use Qdrant\Models\Filter\Condition\ConditionInterface;

class Nested implements ConditionInterface
{
    protected array $container = [];

    public function __construct(string $key, Filter $filter)
    {
        $this->container = [
            'key' => $key,
            'filter' => $filter->toArray()
        ];
    }

    public function toArray(): array
    {
        return [
            'nested' => $this->container
        ];
    }
}
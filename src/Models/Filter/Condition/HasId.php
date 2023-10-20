<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class HasId implements ConditionInterface
{
    public function __construct(protected array $ids)
    {
    }

    public function toArray(): array
    {
        return [
            'has_id' => $this->ids,
        ];
    }
}
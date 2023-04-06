<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class IsEmpty extends AbstractCondition implements ConditionInterface
{
    public function toArray(): array
    {
        return [
            'is_empty' => [
                'key' => $this->key
            ],
        ];
    }
}
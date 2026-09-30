<?php
/**
 * IsNull
 *
 * Matches points where the payload field exists and has a NULL value.
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class IsNull extends AbstractCondition implements ConditionInterface
{
    public function toArray(): array
    {
        return [
            'is_null' => [
                'key' => $this->key
            ],
        ];
    }
}

<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class MatchString extends AbstractCondition implements ConditionInterface
{
    public function __construct(string $key, protected string $value)
    {
        parent::__construct($key);
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'match' => [
                'value' => $this->value
            ]
        ];
    }
}
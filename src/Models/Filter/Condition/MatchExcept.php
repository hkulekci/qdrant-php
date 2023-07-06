<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class MatchExcept extends AbstractCondition implements ConditionInterface
{
    protected array $values = [];

    public function __construct(string $key, array $values)
    {
        parent::__construct($key);
        $this->values = $values;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'match' => [
                'except' => $this->values
            ]
        ];
    }
}
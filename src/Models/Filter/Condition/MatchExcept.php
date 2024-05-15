<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class MatchExcept extends AbstractCondition implements ConditionInterface
{
    /**
     * @var mixed[]
     */
    protected $values = [];

    public function __construct(string $key, array $values = [])
    {
        $this->values = $values;
        parent::__construct($key);
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
<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class MatchBool extends AbstractCondition implements ConditionInterface
{
    /**
     * @var bool
     */
    protected $value;

    public function __construct(string $key, bool $value)
    {
        $this->value = $value;
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
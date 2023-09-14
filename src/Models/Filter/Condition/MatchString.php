<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class MatchString extends AbstractCondition implements ConditionInterface
{
    /**
     * @var string
     */
    protected $value;

    public function __construct(string $key, string $value)
    {
        parent::__construct($key);
        $this->value = $value;
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
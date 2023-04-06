<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class FullTextMatch extends AbstractCondition implements ConditionInterface
{
    protected string $text;

    public function __construct(string $key, string $text)
    {
        parent::__construct($key);
        $this->text = $text;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'match' => [
                'text' => $this->text
            ]
        ];
    }
}
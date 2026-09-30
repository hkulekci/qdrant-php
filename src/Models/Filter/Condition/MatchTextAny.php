<?php
/**
 * MatchTextAny
 *
 * Full-text match of any of the query terms. Available since Qdrant 1.16.
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class MatchTextAny extends AbstractCondition implements ConditionInterface
{
    public function __construct(string $key, protected string $text)
    {
        parent::__construct($key);
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'match' => [
                'text_any' => $this->text
            ]
        ];
    }
}

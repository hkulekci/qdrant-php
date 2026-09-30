<?php
/**
 * MatchPhrase
 *
 * Full-text match of the exact phrase. The text index must be created with `phrase_matching: true`. Available since Qdrant 1.15.
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class MatchPhrase extends AbstractCondition implements ConditionInterface
{
    public function __construct(string $key, protected string $phrase)
    {
        parent::__construct($key);
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'match' => [
                'phrase' => $this->phrase
            ]
        ];
    }
}

<?php
/**
 * MatchPrefix
 *
 * Matches keyword values starting with the given prefix. The keyword index must be created with `prefix: true`. Available since Qdrant 1.19.
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class MatchPrefix extends AbstractCondition implements ConditionInterface
{
    public function __construct(string $key, protected string $prefix)
    {
        parent::__construct($key);
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'match' => [
                'prefix' => $this->prefix
            ]
        ];
    }
}

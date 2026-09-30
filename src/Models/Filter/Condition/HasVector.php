<?php
/**
 * HasVector
 *
 * Matches points which have the given named vector. Use an empty string for the default vector.
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class HasVector implements ConditionInterface
{
    public function __construct(protected string $vectorName)
    {
    }

    public function toArray(): array
    {
        return [
            'has_vector' => $this->vectorName,
        ];
    }
}

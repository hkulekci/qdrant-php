<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class HasId implements ConditionInterface
{
    /**
     * @var mixed[]
     */
    protected $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function toArray(): array
    {
        return [
            'has_id' => $this->ids,
        ];
    }
}
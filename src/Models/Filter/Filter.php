<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter;

use Qdrant\Models\Filter\Condition\ConditionInterface;

class Filter implements ConditionInterface
{
    /**
     * @var mixed[]
     */
    protected $must = [];
    /**
     * @var mixed[]
     */
    protected $must_not = [];
    /**
     * @var mixed[]
     */
    protected $should = [];

    public function addMust(ConditionInterface $condition): Filter
    {
        $this->must[] = $condition;

        return $this;
    }

    public function addMustNot(ConditionInterface $condition): Filter
    {
        $this->must_not[] = $condition;

        return $this;
    }

    public function addShould(ConditionInterface $condition): Filter
    {
        $this->should[] = $condition;

        return $this;
    }

    public function toArray(): array
    {
        $filter = [];
        if ($this->must) {
            $filter['must'] = [];
            foreach ($this->must as $must) {
                /** ConditionInterface $must */
                $filter['must'][] = $must->toArray();
            }
        }
        if ($this->must_not) {
            $filter['must_not'] = [];
            foreach ($this->must_not as $must_not) {
                /** ConditionInterface $must */
                $filter['must_not'][] = $must_not->toArray();
            }
        }
        if ($this->should) {
            $filter['should'] = [];
            foreach ($this->should as $should) {
                /** ConditionInterface $must */
                $filter['should'][] = $should->toArray();
            }
        }

        return $filter;
    }
}
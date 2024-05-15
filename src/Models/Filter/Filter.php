<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter;

use Qdrant\Models\Filter\Condition\ConditionInterface;

class Filter implements ConditionInterface
{
    protected array $must = [];
    protected array $must_not = [];
    protected array $should = [];
    protected array $minShould = [];
    protected ?int $minShouldCount;

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

    public function addMinShould(ConditionInterface $condition): Filter
    {
        $this->minShould[] = $condition;

        return $this;
    }

    public function setMinShouldCount(int $count): Filter
    {
        $this->minShouldCount = $count;

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
        if ($this->minShould && $this->minShouldCount) {
            $filter['min_should'] = [
                'conditions' => [],
                'min_count' => $this->minShouldCount
            ];
            foreach ($this->minShould as $should) {
                /** ConditionInterface $must */
                $filter['min_should']['conditions'][] = $should->toArray();
            }
        }

        return $filter;
    }
}
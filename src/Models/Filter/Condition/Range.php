<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

use Qdrant\Domain\Assert;

class Range extends AbstractCondition implements ConditionInterface
{
    protected const CONDITIONS = ['gt', 'gte', 'lt', 'lte'];
    /**
     * @var mixed[]
     */
    protected $ranges;

    public function __construct(string $key, array $ranges)
    {
        $this->ranges = $ranges;
        parent::__construct($key);
        Assert::keysExistsAtLeastOne(
            $ranges,
            self::CONDITIONS,
            'Range expects at least one of %s keys'
        );
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'range' => $this->ranges
        ];
    }
}
<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

use Qdrant\Domain\Assert;
use Qdrant\Exception\InvalidArgumentException;

class Range extends AbstractCondition implements ConditionInterface
{
    protected const CONDITIONS = ['gt', 'gte', 'lt', 'lte'];

    /**
     * @var array
     */
    protected $ranges;

    public function __construct(string $key, array $ranges)
    {
        parent::__construct($key);
        Assert::keysExistsAtLeastOne(
            $ranges,
            self::CONDITIONS,
            'Range expects at least one of %s keys'
        );
        $this->ranges = $ranges;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'range' => $this->ranges
        ];
    }
}
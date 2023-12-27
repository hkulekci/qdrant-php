<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

use Qdrant\Domain\Assert;

class GeoRadius extends AbstractCondition implements ConditionInterface
{
    protected const CONDITIONS = ['center', 'radius'];

    /**
     * @var array
     */
    protected $radius;

    public function __construct(string $key, array $radius)
    {
        parent::__construct($key);
        Assert::keysExists(
            $radius, self::CONDITIONS, 'Radius expects %s key'
        );

        Assert::keysExists($radius['center'], ['lat', 'lon'], 'Radius center parameter expected lat and lon');

        $this->radius = $radius;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'geo_radius' => $this->radius
        ];
    }
}
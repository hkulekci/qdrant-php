<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

use Qdrant\Domain\Assert;

class GeoBoundingBox extends AbstractCondition implements ConditionInterface
{
    protected const CONDITIONS = ['bottom_right', 'top_left'];

    /**
     * @var array
     */
    protected $boundingBox;

    public function __construct(string $key, array $boundingBox)
    {
        parent::__construct($key);
        Assert::keysExists(
            $boundingBox,
            self::CONDITIONS,
            'BoundingBox expects %s key'
        );

        Assert::allKeyExists(array_values($boundingBox), 'lat', 'All geo locations need to provide "lat" key');
        Assert::allKeyExists(array_values($boundingBox), 'lon', 'All geo locations need to provide "lon" key');

        $this->boundingBox = $boundingBox;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'geo_bounding_box' => $this->boundingBox
        ];
    }
}
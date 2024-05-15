<?php
/**
 * @since     May 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Domain\Assert;

class GeoPolygon extends AbstractCondition implements ConditionInterface
{
    public function __construct(string $key, protected array $exterior, protected ?array $interiors = null)
    {
        parent::__construct($key);

        if (empty($this->exterior)) {
            throw new InvalidArgumentException('Exteriors required!');
        }

        foreach ($this->exterior as $point) {
            Assert::keysExists($point, ['lat', 'lon'], 'Each point of polygon needs lat and lon parameters');
        }
        if ($interiors) {
            foreach ($this->interiors as $point) {
                Assert::keysExists($point, ['lat', 'lon'], 'Each point of polygon needs lat and lon parameters');
            }
        }
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'geo_polygon' => [
                'exterior' => $this->exterior,
                'interiors' => $this->interiors ?? []
            ]
        ];
    }
}
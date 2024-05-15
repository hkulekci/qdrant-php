<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models;

use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class PointsStruct
{
    use ProtectedPropertyAccessor;

    /**
     * @var mixed[]
     */
    protected $points = [];

    public static function createFromArray(array $points): PointsStruct
    {
        $pointsStruct = new self();
        foreach ($points as $point) {
            $pointsStruct->addPoint(PointStruct::createFromArray($point));
        }

        return $pointsStruct;
    }

    public function addPoints(array $points): void
    {
        foreach ($points as $point) {
            $this->addPoint($point);
        }
    }

    public function addPoint(PointStruct $pointStruct): void
    {
        $this->points[] = $pointStruct;
    }

    public function toArray(): array
    {
        $points = [];
        /** @var PointStruct $point */
        foreach ($this->points as $point) {
            $points[] = $point->toArray();
        }

        return $points;
    }

    public function count(): int
    {
        return count($this->points);
    }
}
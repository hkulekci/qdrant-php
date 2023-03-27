<?php
/**
 * BatchPoint
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\PointStruct;

class PointsBatch implements RequestModel
{
    protected array $batchPoints;

    public function __construct()
    {
        $this->batchPoints = [
            'ids' => [],
            'payloads' => [],
            'vectors' => [],
        ];
    }

    public function addPoint(PointStruct $point): void
    {
        $pointAr = $point->toArray();

        $this->batchPoints['ids'][] = $pointAr['id'];
        $this->batchPoints['vectors'][] = $pointAr['vector'];
        $this->batchPoints['payloads'][] = $pointAr['payload'] ?: null;
    }

    public function toArray(): array
    {
        return $this->batchPoints;
    }
}
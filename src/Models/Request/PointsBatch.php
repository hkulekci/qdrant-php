<?php
/**
 * Points Batch
 *
 * @since     Jun 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\PointStruct;
use Qdrant\Models\VectorStruct;

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
        $this->batchPoints['ids'][] = $point->getId();
        $this->batchPoints['vectors'][] = $point->getVector();
        $this->batchPoints['payloads'][] = $point->getPayload();
    }

    public function toArray(): array
    {
        if (
            count($this->batchPoints['ids']) === 0 ||
            count($this->batchPoints['vectors']) !== count($this->batchPoints['ids'])
        ) {
            throw new InvalidArgumentException('You need to add at least one point and the number of id should match with vectors.!');
        }
        $isNamed = $this->batchPoints['vectors'][0]->isNamed();

        $vectors = [];
        $numberOfVectors = count($this->batchPoints['vectors']);
        if ($isNamed) {
            /** @var VectorStruct $vector */
            foreach ($this->batchPoints['vectors'] as $index => $vector) {
                $vectorArr = $vector->toArray();
                foreach ($vectorArr as $name => $item) {
                    if (!isset($vectors[$name])) {
                        $vectors[$name] = array_fill(0, $numberOfVectors, null);
                    }
                    $vectors[$name][$index] = $item;
                }
            }
        } else {
            /** @var VectorStruct $vector */
            foreach ($this->batchPoints['vectors'] as $vector) {
                $vectors[] = $vector->toArray();
            }
        }

        $this->batchPoints['vectors'] = $vectors;

        return $this->batchPoints;
    }
}
<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\PointStruct;
use Qdrant\Models\Request\PointsBatch;
use Qdrant\Models\VectorStruct;

class PointsBatchTest extends TestCase
{
    public function testValidPointsBatch(): void
    {
        $batch = new PointsBatch();

        $batch->addPoint(new PointStruct(
            1,
            new VectorStruct([0.9, 0.1, 0.1], 'image'),
            ['color' => 'red']
        ));
        $batch->addPoint(new PointStruct(
            2,
            new VectorStruct([0.9, 0.1, 0.1], 'details'),
            ['color' => 'green']
        ));
        $batch->addPoint(new PointStruct(
            3,
            new VectorStruct([0.9, 0.1, 0.1], 'image'),
            ['color' => 'blue']
        ));

        $this->assertEquals([
            'ids' => [1, 2, 3],
            'vectors' => [
                'image' => [
                    [0.9, 0.1, 0.1],
                    null,
                    [0.9, 0.1, 0.1],
                ],
                'details' => [
                    null,
                    [0.9, 0.1, 0.1],
                    null,
                ]
            ],
            'payloads' => [
                ['color' => 'red'],
                ['color' => 'green'],
                ['color' => 'blue']
            ],
        ], $batch->toArray());
    }
}
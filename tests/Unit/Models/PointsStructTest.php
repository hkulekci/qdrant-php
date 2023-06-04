<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\PointStruct;
use Qdrant\Models\VectorStruct;

class PointsStructTest extends TestCase
{
    public function testPointsStruct(): void
    {
        $points = new PointsStruct();
        $points->addPoint(
            new PointStruct(
                1,
                new VectorStruct([1, 2, 3])
            )
        );

        $this->assertEquals(
            [
                [
                    'id' => 1,
                    'vector' => [1, 2, 3]
                ]
            ],
            $points->toArray()
        );
    }

    public function testPointsStructWithArray(): void
    {
        $points = PointsStruct::createFromArray([
            [
                'id' => 1,
                'vector' => [1, 2, 3]
            ]
        ]);

        $this->assertEquals(
            [
                [
                    'id' => 1,
                    'vector' => [1, 2, 3]
                ]
            ],
            $points->toArray()
        );
    }

    public function testPointsStructWithNamedVectors(): void
    {
        $points = PointsStruct::createFromArray([
            [
                'id' => 1,
                'vector' => [
                    'image' => [1, 2, 3]
                ],
            ],
            [
                'id' => 1,
                'vector' => [
                    'image' => [3, 4, 5]
                ],
            ]
        ]);

        $this->assertEquals(
            [
                [
                    'id' => 1,
                    'vector' => [
                        'image' => [1, 2, 3]
                    ],
                ],
                [
                    'id' => 1,
                    'vector' => [
                        'image' => [3, 4, 5]
                    ],
                ]
            ],
            $points->toArray()
        );
    }
}
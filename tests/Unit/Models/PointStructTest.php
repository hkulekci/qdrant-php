<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\PointStruct;
use Qdrant\Models\VectorStruct;

class PointStructTest extends TestCase
{
    public function testPointStruct(): void
    {
        $point = new PointStruct(1, new VectorStruct([1, 2, 3]));

        $this->assertEquals(
            [
                'id' => 1,
                'vector' => [1, 2, 3]
            ],
            $point->toArray()
        );
    }

    public function testPointStructWithArray(): void
    {
        $points = PointStruct::createFromArray([
            'id' => 1,
            'vector' => [1, 2, 3]
        ]);

        $this->assertEquals(
            [
                'id' => 1,
                'vector' => [1, 2, 3]
            ],
            $points->toArray()
        );
    }

    public function testPointStructWithMissingFields(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $points = PointStruct::createFromArray([
            'id' => 1,
        ]);
    }
}
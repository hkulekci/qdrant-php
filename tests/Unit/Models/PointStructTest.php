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

    public function testPointStructWithUUid(): void
    {
        $point = new PointStruct('550e8400-e29b-41d4-a716-446655440000', new VectorStruct([1, 2, 3]));

        $this->assertEquals(
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
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
        $this->expectExceptionMessage('Missing point keys');
        $points = PointStruct::createFromArray([
            'id' => 1,
        ]);
    }

    public function testPointStructWithWrongObject(): void
    {
        $class = new class {
            /**
             * @var int
             */
            public $id = 1;
            /**
             * @var mixed[]
             */
            public $vector = [1, 2, 3];
        };
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid vector type');
        $points = PointStruct::createFromArray([
            'id' => 1,
            'vector' => $class
        ]);
    }
}
<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\Point;
use Qdrant\Models\VectorStruct;

class PointTest extends TestCase
{
    public function testValidBasic(): void
    {
        $point = new Point(
            '1',
            new VectorStruct([1, 2, 3])
        );

        $this->assertEquals([
            'id' => '1',
            'vector' => [1, 2, 3]
        ], $point->toArray());
    }

    public function testValidBasicWithArrayVector(): void
    {
        $point = new Point(
            '1',
            [1, 2, 3]
        );

        $this->assertEquals([
            'id' => '1',
            'vector' => [1, 2, 3]
        ], $point->toArray());
    }

    public function testValidBasicWithPayload(): void
    {
        $point = new Point(
            '1',
            [1, 2, 3],
            ['foo' => 'bar']
        );

        $this->assertEquals([
            'id' => '1',
            'vector' => [1, 2, 3],
            'payload' => ['foo' => 'bar']
        ], $point->toArray());
    }
}

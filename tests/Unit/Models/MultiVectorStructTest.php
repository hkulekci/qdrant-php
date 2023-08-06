<?php
/**
 * @since     Aug 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\MultiVectorStruct;
use Qdrant\Models\VectorStruct;

class MultiVectorStructTest extends TestCase
{
    public function testMultiVectorStruct(): void
    {
        $vector = new MultiVectorStruct([[1, 2, 3], [1, 2, 4]]);

        $this->assertEquals(
            [[1, 2, 3], [1, 2, 4]],
            $vector->toArray()
        );
    }

    public function testNamedMultiVectorStruct(): void
    {
        $vector = new MultiVectorStruct([
            'foo' => [1, 2, 3],
            'bar' => [1, 2, 4],
        ]);

        $this->assertEquals(
            [
                'foo' => [1, 2, 3],
                'bar' => [1, 2, 4],
            ],
            $vector->toArray()
        );

        $this->assertEquals(
            [
                'name' => 'foo',
                'vector' => [1, 2, 3]
            ],
            $vector->toSearchArray('foo')
        );

        $this->assertEquals(
            [
                'name' => 'bar',
                'vector' => [1, 2, 4]
            ],
            $vector->toSearchArray('bar')
        );
    }

    public function testNamedMultiVectorStructWithMissingName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Vector with name uber not found");

        $vector = new MultiVectorStruct([
            'foo' => [1, 2, 3],
            'bar' => [1, 2, 4],
        ]);

        $vector->toSearchArray('uber');
    }

    public function testMultiVectorStructCount(): void
    {
        $vector = new MultiVectorStruct([
            'foo' => [1, 2, 3],
            'bar' => [1, 2, 4],
        ]);

        $this->assertEquals(2, $vector->count());
    }
}
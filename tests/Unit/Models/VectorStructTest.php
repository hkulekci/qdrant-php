<?php
/**
 * @since     Aug 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\VectorStruct;

class VectorStructTest extends TestCase
{
    public function testVectorStruct(): void
    {
        $vector = new VectorStruct([1, 2, 3]);

        $this->assertEquals(
            [1, 2, 3],
            $vector->toArray()
        );


        $this->assertEquals(
            [1, 2, 3],
            $vector->toSearchArray()
        );
    }

    public function testNamedVectorStruct(): void
    {
        $vector = new VectorStruct([1, 2, 3], 'foo');

        $this->assertEquals(
            [
                'foo' => [1, 2, 3]
            ],
            $vector->toArray()
        );

        $this->assertEquals(
            [
                'name' => 'foo',
                'vector' => [1, 2, 3]
            ],
            $vector->toSearchArray()
        );
    }
}
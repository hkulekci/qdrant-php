<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\VectorParams;

class VectorParamsTest extends TestCase
{
    public function testInvalidVectorParamsDistance(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new VectorParams(300, 'other-distance');
    }

    public function testValidVectorParams(): void
    {
        $vector = new VectorParams(300, VectorParams::DISTANCE_COSINE);
        $this->assertEquals(
            [
                'size' => 300,
                'distance' => 'Cosine'
            ],
            $vector->toArray()
        );
    }
}
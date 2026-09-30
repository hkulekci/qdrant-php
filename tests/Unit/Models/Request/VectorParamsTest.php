<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\DisabledQuantization;
use Qdrant\Models\Request\CollectionConfig\HnswConfig;
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\CollectionConfig\TurboQuantization;
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

    public function testManhattanDistance(): void
    {
        $vector = new VectorParams(8, VectorParams::DISTANCE_MANHATTAN);

        $this->assertEquals(['size' => 8, 'distance' => 'Manhattan'], $vector->toArray());
    }

    public function testTurbo4Datatype(): void
    {
        $vector = (new VectorParams(1536, VectorParams::DISTANCE_COSINE))
            ->setDatatype(VectorParams::DATATYPE_TURBO4);

        $this->assertEquals(
            [
                'size' => 1536,
                'distance' => 'Cosine',
                'datatype' => 'turbo4',
            ],
            $vector->toArray()
        );
    }

    public function testInvalidDatatype(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new VectorParams(3, VectorParams::DISTANCE_DOT))->setDatatype('int4');
    }

    public function testWithAllParameters(): void
    {
        $vector = (new VectorParams(128, VectorParams::DISTANCE_DOT))
            ->setHnswConfig((new HnswConfig())->setM(0))
            ->setQuantizationConfig(new TurboQuantization(TurboQuantization::BITS_4))
            ->setOnDisk(true)
            ->setMemory(Memory::COLD)
            ->setDatatype(VectorParams::DATATYPE_FLOAT16)
            ->setMultivectorConfig();

        $this->assertEquals(
            [
                'size' => 128,
                'distance' => 'Dot',
                'hnsw_config' => ['m' => 0],
                'quantization_config' => ['turbo' => ['bits' => 'bits4']],
                'on_disk' => true,
                'memory' => 'cold',
                'datatype' => 'float16',
                'multivector_config' => ['comparator' => 'max_sim'],
            ],
            $vector->toArray()
        );
    }

    public function testWithDisabledQuantization(): void
    {
        $vector = (new VectorParams(3, VectorParams::DISTANCE_EUCLID))
            ->setQuantizationConfig(new DisabledQuantization());

        $this->assertEquals('Disabled', $vector->toArray()['quantization_config']);
    }
}

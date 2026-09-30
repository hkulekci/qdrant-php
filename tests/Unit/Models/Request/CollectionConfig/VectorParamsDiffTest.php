<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\DisabledQuantization;
use Qdrant\Models\Request\CollectionConfig\HnswConfig;
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\CollectionConfig\TurboQuantization;
use Qdrant\Models\Request\CollectionConfig\VectorParamsDiff;

class VectorParamsDiffTest extends TestCase
{
    public function testBasic(): void
    {
        $this->assertEquals([], (new VectorParamsDiff())->toArray());
    }

    public function testWithAllParameters(): void
    {
        $diff = (new VectorParamsDiff())
            ->setHnswConfig((new HnswConfig())->setM(32))
            ->setQuantizationConfig(new TurboQuantization(TurboQuantization::BITS_2))
            ->setOnDisk(true)
            ->setMemory(Memory::CACHED);

        $this->assertEquals([
            'hnsw_config' => ['m' => 32],
            'quantization_config' => ['turbo' => ['bits' => 'bits2']],
            'on_disk' => true,
            'memory' => 'cached',
        ], $diff->toArray());
    }

    public function testWithDisabledQuantization(): void
    {
        $diff = (new VectorParamsDiff())->setQuantizationConfig(new DisabledQuantization());

        $this->assertEquals(['quantization_config' => 'Disabled'], $diff->toArray());
    }
}

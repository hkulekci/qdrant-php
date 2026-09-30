<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\CollectionConfig\ScalarQuantization;

class ScalarQuantizationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new ScalarQuantization('int8');

        $this->assertEquals(['scalar' => ['type' => 'int8']], $config->toArray());
    }

    public function testWithAllParameters(): void
    {
        $config = new ScalarQuantization('int8', 1.0, true);

        $this->assertEquals([
            'scalar' => [
                'type' => 'int8',
                'quantile' => 1.0,
                'always_ram' => true,
            ]
        ], $config->toArray());
    }

    public function testWithMemory(): void
    {
        $config = new ScalarQuantization(ScalarQuantization::TYPE_INT8, 0.99, memory: Memory::CACHED);

        $this->assertEquals([
            'scalar' => [
                'type' => 'int8',
                'quantile' => 0.99,
                'memory' => 'cached',
            ]
        ], $config->toArray());
    }
}

<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\CollectionConfig\TurboQuantization;

class TurboQuantizationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new TurboQuantization();

        $this->assertEquals(['turbo' => new \stdClass()], $config->toArray());
        $this->assertEquals('{"turbo":{}}', json_encode($config->toArray()));
    }

    public function testWithBits(): void
    {
        $config = new TurboQuantization(TurboQuantization::BITS_4);

        $this->assertEquals(['turbo' => ['bits' => 'bits4']], $config->toArray());
    }

    public function testWithAllParameters(): void
    {
        $config = new TurboQuantization(TurboQuantization::BITS_1_5, Memory::PINNED);

        $this->assertEquals([
            'turbo' => [
                'bits' => 'bits1_5',
                'memory' => 'pinned',
            ]
        ], $config->toArray());
    }

    public function testWithInvalidBits(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TurboQuantization('bits3');
    }

    public function testWithInvalidMemory(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid memory value "hot"');

        new TurboQuantization(null, 'hot');
    }
}

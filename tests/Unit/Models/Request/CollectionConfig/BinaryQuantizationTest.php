<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\BinaryQuantization;
use Qdrant\Models\Request\CollectionConfig\Memory;

class BinaryQuantizationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new BinaryQuantization();

        $this->assertEquals(['binary' => new \stdClass()], $config->toArray());
    }

    public function testWithAlwaysRamTrue(): void
    {
        $config = new BinaryQuantization(true);

        $this->assertEquals([
            'binary' => [
                'always_ram' => true
            ]
        ], $config->toArray());
    }

    public function testWithAlwaysRamFalse(): void
    {
        $config = new BinaryQuantization(false);

        $this->assertEquals([
            'binary' => [
                'always_ram' => false
            ]
        ], $config->toArray());
    }

    public function testWithEncodings(): void
    {
        $config = new BinaryQuantization(
            encoding: BinaryQuantization::ENCODING_TWO_BITS,
            queryEncoding: BinaryQuantization::QUERY_ENCODING_SCALAR_8BITS,
            memory: Memory::PINNED
        );

        $this->assertEquals([
            'binary' => [
                'encoding' => 'two_bits',
                'query_encoding' => 'scalar8bits',
                'memory' => 'pinned',
            ]
        ], $config->toArray());
    }

    public function testEmptyBinaryIsJsonObject(): void
    {
        $this->assertEquals('{"binary":{}}', json_encode((new BinaryQuantization())->toArray()));
    }
}

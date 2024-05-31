<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\HnswConfig;

class HnswConfigTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new HnswConfig();

        $this->assertEquals([], $config->toArray());
    }

    public function testWithM(): void
    {
        $config = (new HnswConfig())->setM(10);

        $this->assertEquals([
            'm' => 10
        ], $config->toArray());
    }

    public function testWithZeroM(): void
    {
        $config = (new HnswConfig())->setM(0);

        $this->assertEquals([
            'm' => 0
        ], $config->toArray());
    }

    public function testWithInvalidM(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('m should be bigger than 0');
        $config = (new HnswConfig())->setM(-1);

        $this->assertEquals([], $config->toArray());
    }

    public function testWithEfConstruct(): void
    {
        $config = (new HnswConfig())->setEfConstruct(10);

        $this->assertEquals([
            'ef_construct' => 10
        ], $config->toArray());
    }

    public function testWithInvalidEfConstruct(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ef_construct should be bigger than 4');
        $config = (new HnswConfig())->setEfConstruct(-1);

        $this->assertEquals([], $config->toArray());
    }

    public function testWithFullScanThreshold(): void
    {
        $config = (new HnswConfig())->setFullScanThreshold(10);

        $this->assertEquals([
            'full_scan_threshold' => 10
        ], $config->toArray());
    }

    public function testWithInvalidFullScanThreshold(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('full_scan_threshold should be bigger than 10');
        $config = (new HnswConfig())->setFullScanThreshold(1);

        $this->assertEquals([], $config->toArray());
    }

    public function testWithMaxIndexingThreads(): void
    {
        $config = (new HnswConfig())->setMaxIndexingThreads(9);

        $this->assertEquals([
            'max_indexing_threads' => 9
        ], $config->toArray());
    }

    public function testWithInvalidMaxIndexingThreads(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('max_indexing_threads should be bigger than 0');
        $config = (new HnswConfig())->setMaxIndexingThreads(-1);

        $this->assertEquals([], $config->toArray());
    }

    public function testWithOnDisk(): void
    {
        $config = (new HnswConfig())->setOnDisk(true);

        $this->assertEquals([
            'on_disk' => 10
        ], $config->toArray());
    }

    public function testWithPayloadM(): void
    {
        $config = (new HnswConfig())->setPayloadM(10);

        $this->assertEquals([
            'payload_m' => 10
        ], $config->toArray());
    }

    public function testWithInvalidPayloadM(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('payload_m should be bigger than 0');
        $config = (new HnswConfig())->setPayloadM(-1);

        $this->assertEquals([], $config->toArray());
    }

    public function testWithMultipleParameters(): void
    {
        $config = (new HnswConfig())->setMaxIndexingThreads(10)->setPayloadM(10);

        $this->assertEquals([
            'max_indexing_threads' => 10,
            'payload_m' => 10,
        ], $config->toArray());
    }
}

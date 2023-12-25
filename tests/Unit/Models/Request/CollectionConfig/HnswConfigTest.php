<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
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

    public function testWithEfConstruct(): void
    {
        $config = (new HnswConfig())->setEfConstruct(10);

        $this->assertEquals([
            'ef_construct' => 10
        ], $config->toArray());
    }

    public function testWithFullScanThreshold(): void
    {
        $config = (new HnswConfig())->setFullScanThreshold(10);

        $this->assertEquals([
            'full_scan_threshold' => 10
        ], $config->toArray());
    }

    public function testWithDeletedThreshold(): void
    {
        $config = (new HnswConfig())->setMaxIndexingThreads(9);

        $this->assertEquals([
            'max_indexing_threads' => 9
        ], $config->toArray());
    }

    public function testWithOnDisk(): void
    {
        $config = (new HnswConfig())->setOnDisk(true);

        $this->assertEquals([
            'on_disk' => 10
        ], $config->toArray());
    }

    public function testWithDefaultSegmentNumber(): void
    {
        $config = (new HnswConfig())->setPayloadM(10);

        $this->assertEquals([
            'payload_m' => 10
        ], $config->toArray());
    }

    public function testWithFlushIntervalSec(): void
    {
        $config = (new HnswConfig())->setMaxIndexingThreads(10)->setPayloadM(10);

        $this->assertEquals([
            'max_indexing_threads' => 10,
            'payload_m' => 10,
        ], $config->toArray());
    }
}

<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\CollectionParams;
use Qdrant\Models\Request\CollectionConfig\HnswConfig;

class CollectionParamsTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new CollectionParams();

        $this->assertEquals([], $config->toArray());
    }

    public function testWithReplicationFactor(): void
    {
        $config = (new CollectionParams())->setReplicationFactor(10);

        $this->assertEquals([
            'replication_factor' => 10
        ], $config->toArray());
    }

    public function testWithWriteConsistencyFactor(): void
    {
        $config = (new CollectionParams())->setWriteConsistencyFactor(10);

        $this->assertEquals([
            'write_consistency_factor' => 10
        ], $config->toArray());
    }

    public function testWithFullScanThreshold(): void
    {
        $config = (new CollectionParams())->setReadFanOutFactor(10);

        $this->assertEquals([
            'read_fan_out_factor' => 10
        ], $config->toArray());
    }

    public function testWithMaxIndexingThreads(): void
    {
        $config = (new CollectionParams())->setOnDiskPayload(true);

        $this->assertEquals([
            'on_disk_payload' => true
        ], $config->toArray());
    }
}

<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\WalConfig;

class WalConfigTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new WalConfig();

        $this->assertEquals([], $config->toArray());
    }

    public function testWithWalCapacityMb(): void
    {
        $config = (new WalConfig())->setWalCapacityMb(10);

        $this->assertEquals([
            'wal_capacity_mb' => 10
        ], $config->toArray());
    }

    public function testWithInvalidM(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('wal_capacity_mb should be bigger than 1');
        $config = (new WalConfig())->setWalCapacityMb(0);

        $this->assertEquals([], $config->toArray());
    }

    public function testWithEfConstruct(): void
    {
        $config = (new WalConfig())->setWalSegmentsAhead(1);

        $this->assertEquals([
            'wal_segments_ahead' => 1
        ], $config->toArray());
    }

    public function testWithInvalidEfConstruct(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('wal_segments_ahead should be bigger than 0');
        $config = (new WalConfig())->setWalSegmentsAhead(-1);

        $this->assertEquals([], $config->toArray());
    }

    public function testWithWalRetainClosed(): void
    {
        $config = (new WalConfig())->setWalSegmentsAhead(0)->setWalRetainClosed(2);

        $this->assertEquals([
            'wal_segments_ahead' => 0,
            'wal_retain_closed' => 2,
        ], $config->toArray());
    }

    public function testWithInvalidWalRetainClosed(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('wal_retain_closed should be bigger than 0');

        (new WalConfig())->setWalRetainClosed(-1);
    }

    public function testWithZeroWalSegmentsAhead(): void
    {
        $config = (new WalConfig())->setWalSegmentsAhead(0);

        $this->assertEquals([
            'wal_segments_ahead' => 0
        ], $config->toArray());
    }
}

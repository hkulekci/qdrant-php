<?php
/**
 * @since     Oct 2023
 *
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
            'wal_capacity_mb' => 10,
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
            'wal_segments_ahead' => 1,
        ], $config->toArray());
    }

    public function testWithInvalidEfConstruct(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('wal_segments_ahead should be bigger than 0');
        $config = (new WalConfig())->setWalSegmentsAhead(-1);

        $this->assertEquals([], $config->toArray());
    }
}

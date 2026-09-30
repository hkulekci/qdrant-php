<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\QuotaConfig;

class QuotaConfigTest extends TestCase
{
    public function testBasic(): void
    {
        $this->assertEquals(['enabled' => true], (new QuotaConfig())->toArray());
        $this->assertEquals(['enabled' => false], (new QuotaConfig(false))->toArray());
    }

    public function testWithAllParameters(): void
    {
        $config = (new QuotaConfig())
            ->setMaxResidentMemoryPercent(90)
            ->setMaxDiskUsagePercent(95)
            ->setReleaseMarginPercent(0);

        $this->assertEquals([
            'enabled' => true,
            'max_resident_memory_percent' => 90,
            'max_disk_usage_percent' => 95,
            'release_margin_percent' => 0,
        ], $config->toArray());
    }

    public function testInvalidPercent(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('max_disk_usage_percent should be between 1 and 100');

        (new QuotaConfig())->setMaxDiskUsagePercent(0);
    }
}

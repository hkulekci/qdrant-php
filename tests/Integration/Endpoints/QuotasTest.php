<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\QuotaConfig;
use Qdrant\Tests\Integration\AbstractIntegration;

class QuotasTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testGetQuotas(): void
    {
        $response = $this->client->quotas()->get();

        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('config', $response['result']);
        $this->assertArrayHasKey('usage', $response['result']);
    }

    /**
     * Re-applies the current configuration, so running this against a shared instance changes nothing.
     *
     * @throws InvalidArgumentException
     */
    public function testUpdateQuotas(): void
    {
        $current = $this->client->quotas()->get()['result']['config'];

        $config = (new QuotaConfig($current['enabled'] ?? false))
            ->setMaxResidentMemoryPercent($current['max_resident_memory_percent'] ?? null)
            ->setMaxDiskUsagePercent($current['max_disk_usage_percent'] ?? null)
            ->setReleaseMarginPercent($current['release_margin_percent'] ?? null);

        $response = $this->client->quotas()->update($config, ['wait' => 'true']);
        $this->assertEquals('ok', $response['status']);

        $this->assertEquals($current, $this->client->quotas()->get()['result']['config']);
    }
}

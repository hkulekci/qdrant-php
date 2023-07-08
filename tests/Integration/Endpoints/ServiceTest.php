<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints;

use Qdrant\Endpoints\Service;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\ServiceLock;
use Qdrant\Tests\Integration\AbstractIntegration;

class ServiceTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testServiceTelemetry(): void
    {
        $service = new Service($this->client);
        $response = $service->telemetry(true);

        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('app', $response['result']);
        $this->assertArrayHasKey('requests', $response['result']);
        $this->assertArrayHasKey('cluster', $response['result']);
        $this->assertArrayHasKey('collections', $response['result']);
        $this->assertArrayHasKey('id', $response['result']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testServiceMetrics(): void
    {
        $service = new Service($this->client);
        $response = $service->metrics(false);

        $this->assertStringContainsString('app_info', $response['content']);
        $this->assertStringContainsString('cluster_enabled', $response['content']);
        $this->assertStringContainsString('collections_total', $response['content']);
        $this->assertStringContainsString('rest_responses_', $response['content']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testServiceGetLocks(): void
    {
        $service = new Service($this->client);
        $response = $service->getLocks();
        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('result', $response);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testServiceSetLocks(): void
    {
        $service = new Service($this->client);

        $response = $service->setLocks(new ServiceLock(true));
        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('result', $response);

        // Revert back to False
        $response = $service->setLocks(new ServiceLock(false));
        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('result', $response);
    }
}
<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints;

use Qdrant\Endpoints\Service;
use Qdrant\Endpoints\Snapshots;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\ServiceLock;
use Qdrant\Tests\Integration\AbstractIntegration;

class SnapshotsTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testSnapshotsGet(): void
    {
        $service = new Snapshots($this->client);
        $response = $service->get();

        $this->assertArrayHasKey('result', $response);
        $this->assertEquals('ok', $response['status']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSnapshotsCreate(): void
    {
        $service = new Snapshots($this->client);
        $response = $service->create();

        $this->assertArrayHasKey('result', $response);
        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('name', $response['result']);
        $this->assertNotEmpty($response['result']['name']);

        $response = $service->get();
        $this->assertNotEmpty($response['result']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSnapshotsDelete(): void
    {
        $service = new Snapshots($this->client);
        $response = $service->create();

        $this->assertArrayHasKey('result', $response);
        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('name', $response['result']);
        $this->assertNotEmpty($response['result']['name']);

        $response = $service->delete($response['result']['name']);
        $this->assertTrue($response['result']);
        $this->assertEquals('ok', $response['status']);
    }
}
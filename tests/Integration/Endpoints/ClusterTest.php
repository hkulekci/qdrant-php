<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints;

use Qdrant\Endpoints\Cluster;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Tests\Integration\AbstractIntegration;

class ClusterTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testClusterInfo(): void
    {
        $cluster = new Cluster($this->client);
        $response = $cluster->info();

        $this->assertArrayHasKey('result', $response);
        $this->assertEquals('ok', $response['status']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testClusterRecover(): void
    {
        $cluster = new Cluster($this->client);
        $response = $cluster->recover();

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('time', $response);
        $this->assertEquals(
            'Service internal error: Qdrant is running in standalone mode',
            $response['status']['error']
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testClusterRemovePeer(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bad request: Distributed mode disabled.');
        $cluster = new Cluster($this->client);
        $response = $cluster->removePeer(1);
    }
}
<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints;

use Qdrant\Endpoints\Cluster;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Exception\ServerException;
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

        if (getenv('QDRANT_CLUSTER_MODE')) {
            $response = $cluster->recover();
            $this->assertEquals('ok', $response['status']);
        } else {
            $this->expectException(ServerException::class);
            $this->expectExceptionCode(500);
            $cluster->recover();
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testClusterRemovePeer(): void
    {
        $cluster = new Cluster($this->client);

        if (getenv('QDRANT_CLUSTER_MODE')) {
            $response = $cluster->removePeer(1);
            $this->assertEquals('ok', $response['status']);
        } else {
            $this->expectException(InvalidArgumentException::class);
            $cluster->removePeer(1);
        }
    }
}
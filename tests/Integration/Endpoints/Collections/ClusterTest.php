<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Tests\Integration\AbstractIntegration;

class ClusterTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testClusterInfo(): void
    {
        $cluster = new Collections\Cluster($this->client);
        $this->createCollections('sample-collection');
        $cluster->setCollectionName('sample-collection');

        $response = $cluster->info();
        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('peer_id', $response['result']);
        $this->assertArrayHasKey('shard_count', $response['result']);
        $this->assertArrayHasKey('local_shards', $response['result']);
        $this->assertEquals('sample-collection', $cluster->getCollectionName());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testClusterUpdate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bad request: Distributed mode disabled');

        $cluster = new Collections\Cluster($this->client);
        $this->createCollections('sample-collection');
        $cluster->setCollectionName('sample-collection');

        $response = $cluster->update([
            'move_shard' => [
                'shard_id' => 0,
                'to_peer_id' => 0,
                'from_peer_id' => 0
            ]
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getCollections('sample-collection')->delete();
    }
}
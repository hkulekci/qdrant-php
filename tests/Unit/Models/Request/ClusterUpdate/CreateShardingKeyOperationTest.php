<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\ClusterUpdate;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\ClusterUpdate\CreateShardingKeyOperation;
use Qdrant\Models\Request\UpdateCollectionCluster;

class CreateShardingKeyOperationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new CreateShardingKeyOperation('foo');

        $this->assertEquals([
            'shard_key' => 'foo'
        ], $config->toArray());
    }

    public function testWithOtherParameters()
    {
        $config = new UpdateCollectionCluster(
            (new CreateShardingKeyOperation('foo'))->setShardsNumber(1)
                ->setReplicationFactor(1)
                ->setPlacement(0)
        );

        $this->assertEquals([
            'create_sharding_key' => [
                'shard_key' => 'foo',
                'shards_number' => 1,
                'replication_factor' => 1,
                'placement' => 0,
            ]
        ], $config->toArray());
    }
}

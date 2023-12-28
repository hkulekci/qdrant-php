<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\ClusterUpdate;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\ClusterUpdate\DropReplicaOperation;
use Qdrant\Models\Request\UpdateCollectionCluster;

class DropReplicaOperationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new DropReplicaOperation(0, 1);

        $this->assertEquals([
            'shard_id' => 0,
            'peer_id' => 1,
        ], $config->toArray());
    }

    public function testWithOtherParameters()
    {
        $config = new UpdateCollectionCluster(
            new DropReplicaOperation(0, 1)
        );

        $this->assertEquals([
            'drop_replica' => [
                'shard_id' => 0,
                'peer_id' => 1,
            ]
        ], $config->toArray());
    }
}

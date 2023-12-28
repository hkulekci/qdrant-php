<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\ClusterUpdate;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\ClusterUpdate\MoveShardOperation;
use Qdrant\Models\Request\UpdateCollectionCluster;

class MoveShardOperationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new MoveShardOperation(0, 2, 1);

        $this->assertEquals([
            'shard_id' => 0,
            'to_peer_id' => 2,
            'from_peer_id' => 1,
        ], $config->toArray());
    }

    public function testWithUpdateCollectionCluster()
    {
        $config = new UpdateCollectionCluster(
            new MoveShardOperation(0, 2, 1)
        );

        $this->assertEquals([
            'move_shard' => [
                'shard_id' => 0,
                'to_peer_id' => 2,
                'from_peer_id' => 1,
            ]
        ], $config->toArray());
    }

    public function testWithMethod(): void
    {
        $config = (new MoveShardOperation(0, 2, 1))
            ->setMethod('snapshot');

        $this->assertEquals([
            'shard_id' => 0,
            'to_peer_id' => 2,
            'from_peer_id' => 1,
            'method' => 'snapshot'
        ], $config->toArray());
    }

    public function testWithInvalidMethod(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Method could be snapshot or stream_record for operations');
        $config = (new MoveShardOperation(0, 2, 1))
            ->setMethod('foo');
    }
}

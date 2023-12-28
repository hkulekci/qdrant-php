<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\ClusterUpdate;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\ClusterUpdate\DropShardingKeyOperation;
use Qdrant\Models\Request\UpdateCollectionCluster;

class DropShardingKeyOperationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new DropShardingKeyOperation('foo');

        $this->assertEquals([
            'shard_key' => 'foo',
        ], $config->toArray());
    }

    public function testWithOtherParameters()
    {
        $config = new UpdateCollectionCluster(
            new DropShardingKeyOperation('foo')
        );

        $this->assertEquals([
            'drop_sharding_key' => [
                'shard_key' => 'foo',
            ]
        ], $config->toArray());
    }
}

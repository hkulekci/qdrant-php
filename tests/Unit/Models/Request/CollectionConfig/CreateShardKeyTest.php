<?php
/**
 * @since     Jun 2024
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\BinaryQuantization;
use Qdrant\Models\Request\CollectionConfig\CreateShardKey;

class CreateShardKeyTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new CreateShardKey(1);

        $this->assertEquals(['shard_key' => 1], $config->toArray());
    }

    public function testWithSameParameters(): void
    {
        $config = new CreateShardKey(1, 1, 0);

        $this->assertEquals([
            'shard_key' => 1,
            'shard_number' => 1,
            'replication_factor' => 0
        ], $config->toArray());
    }

    public function testWithAllParameters(): void
    {
        $config = new CreateShardKey(1, 1, 0, [1, 2, 3]);

        $this->assertEquals([
            'shard_key' => 1,
            'shard_number' => 1,
            'replication_factor' => 0,
            'placement' => [1, 2, 3]
        ], $config->toArray());
    }

    public function testWithAllParametersEmptyArrayOfPlacement(): void
    {
        $config = new CreateShardKey(1, 1, 0, []);

        $this->assertEquals([
            'shard_key' => 1,
            'shard_number' => 1,
            'replication_factor' => 0,
            'placement' => []
        ], $config->toArray());
    }
}

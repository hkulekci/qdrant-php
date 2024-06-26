<?php
/**
 * @since     Jun 2024
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\BinaryQuantization;
use Qdrant\Models\Request\CollectionConfig\CreateShardKey;
use Qdrant\Models\Request\CollectionConfig\DeleteShardKey;

class DeleteShardKeyTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new DeleteShardKey(1);

        $this->assertEquals(['shard_key' => 1], $config->toArray());
    }

    public function testBasic2(): void
    {
        $config = new DeleteShardKey(0);

        $this->assertEquals(['shard_key' => 0], $config->toArray());
    }
}

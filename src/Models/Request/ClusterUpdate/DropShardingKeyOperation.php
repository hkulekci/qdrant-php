<?php
/**
 * @since     Dec 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\ClusterUpdate;

class DropShardingKeyOperation implements Operation
{
    /**
     * @var string
     */
    protected $shardKey;

    public function __construct(string $shardKey)
    {
        $this->shardKey = $shardKey;
    }

    public function getKey(): string
    {
        return 'drop_sharding_key';
    }

    public function toArray(): array
    {
        return [
            'shard_key' => $this->shardKey,
        ];
    }
}
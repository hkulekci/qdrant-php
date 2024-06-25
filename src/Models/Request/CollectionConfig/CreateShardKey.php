<?php
/**
 * CreateShardKey
 *
 * @since     Jun 2024
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Models\Request\RequestModel;

class CreateShardKey implements RequestModel
{
    public function __construct(
        protected int|string $shardKey,
        protected ?int $shardNumber = null,
        protected ?int $replicationFactor = null,
        protected ?array $placement = null
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'shard_key' => $this->shardKey,
            'shard_number' => $this->shardNumber,
            'replication_factor' => $this->replicationFactor,
            'placement' => $this->placement,
        ], function($val) {
            return !is_null($val);
        });
    }
}
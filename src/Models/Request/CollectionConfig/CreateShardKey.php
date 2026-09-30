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
    /**
     * @param string|null $initialState Initial state of the new replicas (e.g. "Active", "Partial"), available since Qdrant 1.16.
     */
    public function __construct(
        protected int|string $shardKey,
        protected ?int $shardNumber = null,
        protected ?int $replicationFactor = null,
        protected ?array $placement = null,
        protected ?string $initialState = null
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'shard_key' => $this->shardKey,
            'shards_number' => $this->shardNumber,
            'replication_factor' => $this->replicationFactor,
            'placement' => $this->placement,
            'initial_state' => $this->initialState,
        ], function($val) {
            return !is_null($val);
        });
    }
}

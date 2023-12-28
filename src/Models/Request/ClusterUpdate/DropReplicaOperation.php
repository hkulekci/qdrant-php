<?php
/**
 * @since     Dec 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\ClusterUpdate;

class DropReplicaOperation implements Operation
{
    public function __construct(
        protected int $shardId,
        protected int $peerId
    ) {}

    public function toArray(): array
    {
        return [
            'shard_id' => $this->shardId,
            'peer_id' => $this->peerId,
        ];
    }

    public function getKey(): string
    {
        return 'drop_replica';
    }
}
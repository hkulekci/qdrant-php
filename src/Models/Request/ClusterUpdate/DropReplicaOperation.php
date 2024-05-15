<?php
/**
 * @since     Dec 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\ClusterUpdate;

class DropReplicaOperation implements Operation
{
    /**
     * @var int
     */
    protected $shardId;
    /**
     * @var int
     */
    protected $peerId;

    public function __construct(int $shardId, int $peerId)
    {
        $this->shardId = $shardId;
        $this->peerId = $peerId;
    }

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
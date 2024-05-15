<?php
/**
 * @since     Dec 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\ClusterUpdate;

use Qdrant\Exception\InvalidArgumentException;

class MoveShardOperation implements Operation
{
    /**
     * @var int
     */
    protected $shardId;
    /**
     * @var int
     */
    protected $toPeerId;
    /**
     * @var int
     */
    protected $fromPeerId;
    /**
     * @var string|null
     */
    protected $method;

    public function __construct(int $shardId, int $toPeerId, int $fromPeerId)
    {
        $this->shardId = $shardId;
        $this->toPeerId = $toPeerId;
        $this->fromPeerId = $fromPeerId;
    }

    public function getKey(): string
    {
        return 'move_shard';
    }

    public function toArray(): array
    {
        return array_filter([
            'shard_id' => $this->shardId,
            'to_peer_id' => $this->toPeerId,
            'from_peer_id' => $this->fromPeerId,
            'method' => $this->method,
        ], static function ($v) {
            return $v !== null;
        });
    }

    public function setMethod(string $method): MoveShardOperation
    {
        if (!in_array($method, ['snapshot', 'stream_records'])) {
            throw new InvalidArgumentException('Method could be snapshot or stream_record for operations');
        }

        $this->method = $method;

        return $this;
    }
}
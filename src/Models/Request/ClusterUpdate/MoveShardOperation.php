<?php
/**
 * @since     Dec 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\ClusterUpdate;

use Qdrant\Exception\InvalidArgumentException;

class MoveShardOperation implements Operation
{
    protected ?string $method = null;

    public function __construct(
        protected int $shardId,
        protected int $toPeerId,
        protected int $fromPeerId,
    ) {
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
        if (!in_array($method, ['snapshot', 'stream_records']))
        {
            throw new InvalidArgumentException('Method could be snapshot or stream_record for operations');
        }

        $this->method = $method;

        return $this;
    }
}

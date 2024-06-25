<?php
/**
 * DeleteShardKey
 *
 * @since     Jun 2024
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Models\Request\RequestModel;

class DeleteShardKey implements RequestModel
{
    public function __construct(
        protected int|string $shardKey
    ) {
    }

    public function toArray(): array
    {
        return [
            'shard_key' => $this->shardKey
        ];
    }
}
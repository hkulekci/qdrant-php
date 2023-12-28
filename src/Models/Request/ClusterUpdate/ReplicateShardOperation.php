<?php
/**
 * @since     Dec 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\ClusterUpdate;

class ReplicateShardOperation extends MoveShardOperation
{
    public function getKey(): string
    {
        return 'replicate_shard';
    }
}
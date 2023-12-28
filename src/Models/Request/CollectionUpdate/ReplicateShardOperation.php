<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionUpdate;

class ReplicateShardOperation extends MoveShardOperation
{
    public function getKey(): string
    {
        return 'replicate_shard';
    }
}
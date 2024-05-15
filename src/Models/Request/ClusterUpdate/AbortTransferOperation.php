<?php
/**
 * @since     Dec 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\ClusterUpdate;

class AbortTransferOperation extends MoveShardOperation
{
    public function getKey(): string
    {
        return 'abort_transfer';
    }
}
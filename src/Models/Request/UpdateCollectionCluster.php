<?php
/**
 * UpdateCollectionCluster
 *
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\Request\ClusterUpdate\Operation;

class UpdateCollectionCluster implements RequestModel
{
    public function __construct(protected Operation $operation)
    {
    }

    public function toArray(): array
    {
        return [
            $this->operation->getKey() => $this->operation->toArray(),
        ];
    }
}

<?php
/**
 * UpdateCollectionCluster
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\Request\ClusterUpdate\Operation;

class UpdateCollectionCluster implements RequestModel
{
    /**
     * @var Operation
     */
    protected $operation;

    /**
     * @param Operation $operation
     */
    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    public function toArray(): array
    {
        return [
            $this->operation->getKey() => $this->operation->toArray(),
        ];
    }
}

<?php
/**
 * Cluster
 *
 * https://qdrant.github.io/qdrant/redoc/#tag/cluster/operation/cluster_status
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints\Collections;

use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\UpdateCollectionCluster;
use Qdrant\Response;

class Cluster extends AbstractEndpoint
{
    /**
     * # Collection cluster info
     * Get cluster information for a collection
     *
     * @throws InvalidArgumentException
     */
    public function info(): Response
    {
        return $this->client->execute(
            $this->createRequest('GET', '/collections/' . $this->getCollectionName() . '/cluster')
        );
    }

    /**
     * # Update collection cluster setup
     *
     * @throws InvalidArgumentException
     */
    public function update(UpdateCollectionCluster $params, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/cluster' . $this->queryBuild($queryParams),
                $params->toArray()
            )
        );
    }
}
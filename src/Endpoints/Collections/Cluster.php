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
use Qdrant\Models\Request\UpdateCollection;
use Qdrant\Response;

class Cluster extends AbstractEndpoint
{
    /**
     * # Collection cluster info
     * Get cluster information for a collection
     *
     * @throws InvalidArgumentException
     */
    public function cluster(string $name): Response
    {
        return $this->client->execute(
            $this->createRequest('GET', '/collections/' . $name . '/cluster')
        );
    }

    /**
     * # Delete collection
     * Drop collection and all associated data
     *
     * @throws InvalidArgumentException
     */
    public function delete(string $name): Response
    {
        return $this->client->execute(
            $this->createRequest('DELETE', '/collections/' . $name)
        );
    }

    /**
     * # Update collection parameters
     * Update parameters of the existing collection
     *
     * @throws InvalidArgumentException
     */
    public function update(string $name, UpdateCollection $params): Response
    {
        return $this->client->execute(
            $this->createRequest('PATCH', '/collections/' . $name, $params->toArray())
        );
    }
}
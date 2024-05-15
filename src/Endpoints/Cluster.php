<?php
/**
 * Cluster
 *
 * https://qdrant.github.io/qdrant/redoc/#tag/cluster/operation/cluster_status
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Response;

class Cluster extends AbstractEndpoint
{
    /**
     * # Get cluster status info
     * Get information about the current state and composition of the cluster
     *
     * @throws InvalidArgumentException
     */
    public function info(): Response
    {
        return $this->client->execute(
            $this->createRequest('GET', '/cluster')
        );
    }

    /**
     * # Tries to recover current peer Raft state.
     *
     * @throws InvalidArgumentException
     */
    public function recover(): Response
    {
        return $this->client->execute(
            $this->createRequest('POST', '/cluster/recover')
        );
    }

    /**
     * # Remove peer from the cluster
     * Tries to remove peer from the cluster. Will return an error if peer has shards on it.
     *
     * @throws InvalidArgumentException
     */
    public function removePeer(int $peerId, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest('DELETE', '/cluster/peer/' . $peerId . $this->queryBuild($queryParams))
        );
    }
}
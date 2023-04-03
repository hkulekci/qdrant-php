<?php
/**
 * Collections
 *
 * https://qdrant.tech/documentation/collections/
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Endpoints;

use Qdrant\Endpoints\Collections\Aliases;
use Qdrant\Endpoints\Collections\Index;
use Qdrant\Endpoints\Collections\Points;
use Qdrant\Endpoints\Collections\Snapshots;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\UpdateCollection;
use Qdrant\Response;

class Collections extends AbstractEndpoint
{
    /**
     * # List collections
     * Get list name of all existing collections
     *
     * @throws InvalidArgumentException
     */
    public function list(): Response
    {
        return $this->client->execute(
            $this->createRequest('GET', '/collections')
        );
    }

    /**
     * # Create collection
     * Create new collection with given parameters
     *
     * @throws InvalidArgumentException
     */
    public function create(string $name, CreateCollection $params): Response
    {
        return $this->client->execute(
            $this->createRequest('PUT', '/collections/' . $name, $params->toArray())
        );
    }

    /**
     * # Collection info
     * Get detailed information about specified existing collection
     *
     * @throws InvalidArgumentException
     */
    public function info(string $name): Response
    {
        return $this->client->execute(
            $this->createRequest('GET', '/collections/' . $name)
        );
    }

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
     * # Collection cluster info
     * Get cluster information for a collection
     *
     * @throws InvalidArgumentException
     */
    public function updateCluster(string $name, array $params): Response
    {
        return $this->client->execute(
            $this->createRequest('POST', '/collections/' . $name . '/cluster', $params)
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

    public function aliases(): Aliases
    {
        return (new Aliases($this->client))->setCollectionName($this->collectionName);
    }

    public function points(): Points
    {
        return (new Points($this->client))->setCollectionName($this->collectionName);
    }

    public function snapshots(): Snapshots
    {
        return (new Snapshots($this->client))->setCollectionName($this->collectionName);
    }

    public function index(): Index
    {
        return (new Index($this->client))->setCollectionName($this->collectionName);
    }
}
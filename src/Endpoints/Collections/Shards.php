<?php
/**
 * Shards
 *
 * https://qdrant.github.io/qdrant/redoc/index.html#tag/collections/operation/create_shard_key
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Endpoints\Collections;

use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\CreateShardKey;
use Qdrant\Models\Request\CollectionConfig\DeleteShardKey;
use Qdrant\Response;

class Shards extends AbstractEndpoint
{
    /**
     * # List shard keys
     * Available since Qdrant 1.17.
     *
     * @throws InvalidArgumentException
     */
    public function list(): Response
    {
        return $this->client->execute(
            $this->createRequest('GET', '/collections/' . $this->getCollectionName() . '/shards')
        );
    }

    public function create(CreateShardKey $params, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/shards' . $this->queryBuild($queryParams),
                $params->toArray()
            )
        );
    }

    public function delete(DeleteShardKey $params, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/shards/delete' . $this->queryBuild($queryParams),
                $params->toArray()
            )
        );
    }
}
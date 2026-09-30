<?php
/**
 * Vectors
 *
 * Add or remove named vectors on an existing collection, available since Qdrant 1.18.
 *
 * https://qdrant.tech/documentation/manage-data/vectors/
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints\Collections;

use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CreateVector;
use Qdrant\Response;

class Vectors extends AbstractEndpoint
{
    /**
     * # Create a named vector
     * Add a new dense or sparse named vector to the collection.
     *
     * @throws InvalidArgumentException
     */
    public function create(string $vectorName, CreateVector $params, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/vectors/' . rawurlencode($vectorName)
                    . $this->queryBuild($queryParams),
                $params->toArray()
            )
        );
    }

    /**
     * # Delete a named vector
     * Remove the named vector and its data from all points of the collection.
     *
     * @throws InvalidArgumentException
     */
    public function delete(string $vectorName, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'DELETE',
                '/collections/' . $this->getCollectionName() . '/vectors/' . rawurlencode($vectorName)
                    . $this->queryBuild($queryParams)
            )
        );
    }
}

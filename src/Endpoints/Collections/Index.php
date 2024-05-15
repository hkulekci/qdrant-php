<?php
/**
 * Index
 *
 * https://qdrant.tech/documentation/indexing/
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints\Collections;

use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CreateIndex;
use Qdrant\Response;

class Index extends AbstractEndpoint
{
    /**
     * Create index for field in collection
     *
     * @throws InvalidArgumentException
     */
    public function create(CreateIndex $params, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/index' . $this->queryBuild($queryParams),
                $params->toArray()
            )
        );
    }

    /**
     * Delete index for field in collection
     *
     * @throws InvalidArgumentException
     */
    public function delete(string $fieldName, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest('DELETE', '/collections/' . $this->getCollectionName() . '/index/' . $fieldName . $this->queryBuild($queryParams))
        );
    }
}
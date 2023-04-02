<?php
/**
 * Index
 *
 * https://qdrant.tech/documentation/indexing/
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

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
    public function create(CreateIndex $params): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/index',
                $params->toArray()
            )
        );
    }
}
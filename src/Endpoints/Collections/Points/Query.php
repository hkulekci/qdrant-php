<?php
/**
 * Query
 *
 * @since     Mar 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints\Collections\Points;

use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\Points\BatchQueryRequest;
use Qdrant\Models\Request\Points\QueryGroupsRequest;
use Qdrant\Models\Request\Points\QueryRequest;
use Qdrant\Response;

class Query extends AbstractEndpoint
{
    /**
     * Universally query points. Covers all capabilities of search, recommend, discover, filters.
     * Also enables hybrid and multi-stage queries.
     *
     * @throws InvalidArgumentException
     */
    public function query(QueryRequest $request, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/query' . $this->queryBuild($queryParams),
                $request->toArray()
            )
        );
    }

    /**
     * Universally query points in batch.
     *
     * @throws InvalidArgumentException
     */
    public function batch(BatchQueryRequest $request, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/query/batch' . $this->queryBuild($queryParams),
                $request->toArray()
            )
        );
    }

    /**
     * Universally query points and group results by a specified payload field.
     *
     * @throws InvalidArgumentException
     */
    public function groups(QueryGroupsRequest $request, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/query/groups' . $this->queryBuild($queryParams),
                $request->toArray()
            )
        );
    }
}

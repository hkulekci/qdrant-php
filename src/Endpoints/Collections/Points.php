<?php
/**
 * Points
 *
 * https://qdrant.tech/documentation/points/
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints\Collections;

use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Endpoints\Collections\Points\Payload;
use Qdrant\Endpoints\Collections\Points\Query;
use Qdrant\Endpoints\Collections\Points\Recommend;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\PointsBatch;
use Qdrant\Models\Request\ScrollRequest;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Response;

class Points extends AbstractEndpoint
{
    public function payload(): Payload
    {
        return (new Payload($this->client))->setCollectionName($this->collectionName);
    }

    /**
     * @deprecated Use query() endpoint instead. The recommend endpoint is deprecated in Qdrant API.
     */
    public function recommend(): Recommend
    {
        return (new Recommend($this->client))->setCollectionName($this->collectionName);
    }

    public function query(): Query
    {
        return (new Query($this->client))->setCollectionName($this->collectionName);
    }

    /**
     * @deprecated Use query() endpoint instead. The search endpoint is deprecated in Qdrant API
     *             and removed from the OpenAPI specification since Qdrant 1.19.
     *
     * @throws InvalidArgumentException
     */
    public function search(SearchRequest $searchParams, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                'collections/' . $this->collectionName . '/points/search' . $this->queryBuild($queryParams),
                $searchParams->toArray()
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function scroll(Filter|ScrollRequest|null $scrollParams = null, array $queryParams = []): Response
    {
        $body = [];
        if ($scrollParams instanceof Filter) {
            $body['filter'] = $scrollParams->toArray();
        } elseif ($scrollParams instanceof ScrollRequest) {
            $body = $scrollParams->toArray();
        }
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/scroll' . $this->queryBuild($queryParams),
                $body
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(array $points, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/delete' . $this->queryBuild($queryParams),
                [
                    'points' => $points,
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function deleteByFilter(Filter $filter): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/delete',
                [
                    'filter' => $filter->toArray(),
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function ids(array $ids, $withPayload = false, $withVector = true, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points' . $this->queryBuild($queryParams),
                [
                    'ids'          => $ids,
                    'with_payload' => $withPayload,
                    'with_vector'  => $withVector,
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function id(int|string $id, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'GET',
                '/collections/' . $this->getCollectionName() . '/points/' . $id . $this->queryBuild($queryParams)
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function count(?Filter $filter = null, $exact = false): Response
    {
        $body = [
            'exact' => $exact,
        ];

        if ($filter) {
            $body['filter'] = $filter->toArray();
        }

        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/count',
                $body
            )
        );
    }

    /**
     * @param string|null $updateMode One of the UpdateMode::* constants, available since Qdrant 1.17.
     * @param Filter|null $updateFilter Only update existing points matching this filter (conditional update),
     *                                  available since Qdrant 1.16.
     *
     * @throws InvalidArgumentException
     */
    public function upsert(
        PointsStruct $points,
        array $queryParams = [],
        ?string $updateMode = null,
        ?Filter $updateFilter = null
    ): Response {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/points' . $this->queryBuild($queryParams),
                [
                    'points' => $points->toArray(),
                ] + $this->updateOptions($updateMode, $updateFilter)
            )
        );
    }

    /**
     * https://api.qdrant.tech/api-reference/points/upsert-points
     *
     * @param string|null $updateMode One of the UpdateMode::* constants, available since Qdrant 1.17.
     * @param Filter|null $updateFilter Only update existing points matching this filter (conditional update),
     *                                  available since Qdrant 1.16.
     *
     * @throws InvalidArgumentException
     */
    public function batch(
        PointsBatch $points,
        array $queryParams = [],
        ?string $updateMode = null,
        ?Filter $updateFilter = null
    ): Response {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/points' . $this->queryBuild($queryParams),
                [
                    'batch' => $points->toArray(),
                ] + $this->updateOptions($updateMode, $updateFilter)
            )
        );
    }

    private function updateOptions(?string $updateMode, ?Filter $updateFilter): array
    {
        $options = [];
        if ($updateMode !== null) {
            $options['update_mode'] = $updateMode;
        }
        if ($updateFilter !== null) {
            $options['update_filter'] = $updateFilter->toArray();
        }

        return $options;
    }
}

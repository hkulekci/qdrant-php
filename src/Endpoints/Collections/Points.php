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
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\PointsBatch;
use Qdrant\Models\Request\RecommendRequest;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Response;

class Points extends AbstractEndpoint
{
    public function payload(): Payload
    {
        return (new Payload($this->client))->setCollectionName($this->collectionName);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function search(SearchRequest $searchParams): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                'collections/' . $this->collectionName . '/points/search',
                $searchParams->toArray()
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function scroll(Filter $filter = null): Response
    {
        $body = [];
        if ($filter) {
            $body['filter'] = $filter->toArray();
        }
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/scroll',
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
                    'ids' => $ids,
                    'with_payload' => $withPayload,
                    'with_vector' => $withVector,
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function id(int $id, array $queryParams = []): Response
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
    public function count(Filter $filter = null, $exact = false): Response
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
     * @throws InvalidArgumentException
     */
    public function upsert(PointsStruct $points, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/points' . $this->queryBuild($queryParams),
                [
                    'points' => $points->toArray(),
                ]
            )
        );
    }

    /**
     * https://qdrant.github.io/qdrant/redoc/index.html#tag/points/operation/upsert_points
     *
     * @throws InvalidArgumentException
     */
    public function batch(PointsBatch $points, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/points' . $this->queryBuild($queryParams),
                [
                    'batch' => $points->toArray(),
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function recommend(RecommendRequest $recommendParams): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                'collections/' . $this->collectionName . '/points/recommend',
                $recommendParams->toArray()
            )
        );
    }
}
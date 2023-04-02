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
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\PointsBatch;
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
    public function scroll(array $filter = []): Response
    {
        $body = [];
        if ($filter) {
            $body['filter'] = $filter;
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
    public function delete(array $points): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/delete',
                [
                    'points' => $points,
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function deleteByFilter(array $filter): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/delete',
                [
                    'filter' => $filter,
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function ids(array $ids): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points',
                [
                    'ids' => $ids,
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function id(int $id): Response
    {
        return $this->client->execute(
            $this->createRequest('GET', '/collections/' . $this->getCollectionName() . '/points/' . $id)
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function count(array $filter = [], $exact = false): Response
    {
        $body = [
            'exact' => $exact,
        ];

        if ($filter) {
            $body['filter'] = $filter;
        }

        //TODO: we can put here filter object instead of array
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
    public function upsert(PointsStruct $points): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/points',
                [
                    'points' => $points->toArray(),
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function batch(PointsBatch $batchPoint): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'PUT',
                '/collections/' . $this->getCollectionName() . '/points',
                [
                    'batch' => $batchPoint->toArray(),
                ]
            )
        );
    }
}
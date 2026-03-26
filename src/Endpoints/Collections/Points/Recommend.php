<?php
/**
 * Payload
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints\Collections\Points;

use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\Points\BatchRecommendRequest;
use Qdrant\Models\Request\Points\GroupRecommendRequest;
use Qdrant\Models\Request\Points\RecommendRequest;
use Qdrant\Response;

/**
 * @deprecated Use Query endpoint instead. The recommend endpoints are deprecated in Qdrant API.
 */
class Recommend extends AbstractEndpoint
{
    /**
     * Retrieves points that are closer to stored positive examples and further from negative examples.
     *
     * @deprecated Use Query::query() instead.
     * @throws InvalidArgumentException
     */
    public function recommend(RecommendRequest $request, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/recommend' . $this->queryBuild($queryParams),
                $request->toArray()
            )
        );
    }

    /**
     * Retrieves points in batches that are closer to stored positive examples and further from negative examples.
     *
     * @deprecated Use Query::batch() instead.
     * @param BatchRecommendRequest $request
     * @param array $queryParams
     * @return Response
     */
    public function batch(BatchRecommendRequest $request, array $queryParams = []): Response
    {

        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/recommend/batch' . $this->queryBuild($queryParams),
                $request->toArray()
            )
        );
    }

    /**
     * @deprecated Use Query::groups() instead.
     * @throws \RuntimeException
     */
    public function groups($request, array $queryParams = []): Response
    {
        throw new \RuntimeException('Not implemented on client!');
    }
}
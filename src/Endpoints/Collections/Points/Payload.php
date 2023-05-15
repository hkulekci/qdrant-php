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
use Qdrant\Models\Filter\Filter;
use Qdrant\Response;

class Payload extends AbstractEndpoint
{
    /**
     * @throws InvalidArgumentException
     */
    public function clear(array $points): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/payload/clear',
                [
                    'points' => $points,
                ]
            )
        );
    }

    /**
     * Delete specified key payload for points
     *
     * @param array $points
     * @param array $keys
     * @param Filter|null $filter
     * @return Response
     * @throws InvalidArgumentException
     */
    public function delete(array $points, array $keys, Filter $filter = null): Response
    {
        $data = [
            'points' => $points,
            'keys' => $keys
        ];
        if ($filter) {
            $data['filters'] = $filter->toArray();
        }

        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/payload/delete',
                $data
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function set(array $points, array $payload, array $params = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/payload' . $this->queryBuild($params),
                [
                    'payload' => $payload,
                    'points' => $points,
                ]
            )
        );
    }
}
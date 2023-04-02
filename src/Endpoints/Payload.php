<?php
/**
 * Payload
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Qdrant\Exception\InvalidArgumentException;
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
     * @param array $filters
     * @return Response
     * @throws InvalidArgumentException
     */
    public function delete(array $points, array $keys, array $filters): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/payload/delete',
                [
                    'filters' => $filters,
                    'keys' => $keys,
                    'points' => $points,
                ]
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function set(array $points, array $payload): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/points/payload',
                [
                    'payload' => $payload,
                    'points' => $points,
                ]
            )
        );
    }
}
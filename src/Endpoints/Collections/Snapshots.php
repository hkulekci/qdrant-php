<?php
/**
 * Snapshots
 *
 * https://qdrant.tech/documentation/snapshots/
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints\Collections;

use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Response;

class Snapshots extends AbstractEndpoint
{
    /**
     * @throws InvalidArgumentException
     */
    public function create(array $params, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                '/collections/' . $this->getCollectionName() . '/snapshots' . $this->queryBuild($queryParams),
                $params
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(string $snapshotName, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'DELETE',
                '/collections/' . $this->getCollectionName() . '/snapshots/' . $snapshotName . $this->queryBuild($queryParams)
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function list(): Response
    {
        return $this->client->execute(
            $this->createRequest('GET', '/collections/' . $this->getCollectionName() . '/snapshots')
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function download(string $snapshotName): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'GET',
                '/collections/' . $this->getCollectionName() . '/snapshots/' . $snapshotName
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function recover(bool $wait = null): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'GET',
                '/collections/' . $this->getCollectionName() . '/snapshots/recover'
            )
        );
    }
}
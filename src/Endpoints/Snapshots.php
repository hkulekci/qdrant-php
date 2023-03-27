<?php
/**
 * Snapshots
 *
 * https://qdrant.tech/documentation/snapshots/
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Response;

class Snapshots extends AbstractEndpoint
{
    /**
     * @throws InvalidArgumentException
     */
    public function create(array $options): Response
    {
        return $this->client->execute('POST', '/collections/' . $this->getCollectionName() . '/snapshots', $options);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(string $name): Response
    {
        return $this->client->execute('DELETE', '/collections/' . $this->getCollectionName() . '/snapshots/' . $name);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function list(): Response
    {
        return $this->client->execute('GET', '/collections/' . $this->getCollectionName() . '/snapshots');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $name): Response
    {
        return $this->client->execute('GET', '/collections/' . $this->getCollectionName() . '/snapshots/' . $name);
    }
}
<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant;

use Psr\Http\Message\RequestInterface;
use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Endpoints\Cluster;
use Qdrant\Endpoints\Collections;
use Qdrant\Endpoints\Service;
use Qdrant\Endpoints\Snapshots;
use Qdrant\Http\HttpClientInterface;

class Qdrant implements ClientInterface
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function collections(string $collectionName = null): Collections
    {
        return (new Collections($this->client))->setCollectionName($collectionName);
    }

    public function snapshots(): Snapshots
    {
        return new Snapshots($this->client);
    }

    public function cluster(): Cluster
    {
        return new Cluster($this->client);
    }

    public function service(): Service
    {
        return new Service($this->client);
    }

    public function execute(RequestInterface $request): Response
    {
        return $this->client->execute($request);
    }

    /**
     * Sends a raw request directly to the Qdrant API, providing flexibility for executing non-standard or newly introduced endpoints.
     *
     * @param string $method The HTTP method to use (e.g., 'GET', 'POST').
     * @param string $uri The API endpoint URI.
     * @param array $body (Optional) The request body, typically used for 'POST' or 'PUT' methods.
     * @return Response The response from the Qdrant API.
     */
    public function executeRaw(string $method, string $uri, array $body = []): Response
    {
        $request = AbstractEndpoint::createRequest($method, $uri, $body);
        return $this->client->execute($request);
    }

}
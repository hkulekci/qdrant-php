<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant;

use Psr\Http\Message\RequestInterface;
use Qdrant\Endpoints\Cluster;
use Qdrant\Endpoints\Collections;
use Qdrant\Endpoints\Service;
use Qdrant\Endpoints\Snapshots;
use Qdrant\Http\HttpClientInterface;

class Qdrant implements ClientInterface
{
    /**
     * @var HttpClientInterface
     */
    private $client;

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
}
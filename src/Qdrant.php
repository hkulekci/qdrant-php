<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant;

use Psr\Http\Message\RequestInterface;
use Qdrant\Endpoints\Collections;
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

    public function execute(RequestInterface $request): Response
    {
        return $this->client->execute($request);
    }
}
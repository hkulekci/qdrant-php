<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Qdrant\Endpoints\Cluster;
use Qdrant\Endpoints\Collections;
use Qdrant\Endpoints\Service;
use Qdrant\Endpoints\Snapshots;
use Qdrant\Http\Transport;

class Qdrant implements ClientInterface
{
    public function __construct(private readonly Transport $transport)
    {
    }

    public function collections(string $collectionName = null): Collections
    {
        return (new Collections($this))->setCollectionName($collectionName);
    }

    public function snapshots(): Snapshots
    {
        return new Snapshots($this);
    }

    public function cluster(): Cluster
    {
        return new Cluster($this);
    }

    public function service(): Service
    {
        return new Service($this);
    }

    public function execute(RequestInterface $request): Response
    {
        try {
            $res = $this->transport->sendRequest($request);

            return new Response($res);
        } catch (RequestExceptionInterface $e) {
            var_dump('RequestException', $e->getMessage(), $request); exit();
            /*$statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode >= 400 && $statusCode < 500) {
                $errorResponse = new Response($e->getResponse());
                throw (new InvalidArgumentException($e->getMessage(), $statusCode))
                    ->setResponse($errorResponse);
            } elseif ($statusCode >= 500) {
                $errorResponse = new Response($e->getResponse());
                throw (new ServerException($e->getMessage(), $statusCode))
                    ->setResponse($errorResponse);
            }*/
        }
    }
}
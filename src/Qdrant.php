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
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Exception\ServerException;
use Qdrant\Http\Transport;

class Qdrant implements ClientInterface
{
    /**
     * @var Transport
     */
    private $transport;

    public function __construct($transport)
    {
        $this->transport = $transport;
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
        $res = $this->transport->sendRequest($request);
        $statusCode = $res->getStatusCode();
        if ($statusCode >= 400 && $statusCode < 500) {
            $errorResponse = new Response($res);
            throw (new InvalidArgumentException(
                $errorResponse['status']['error'] ?? 'Invalid Argument Exception',
                $statusCode)
            )->setResponse($errorResponse);
        } elseif ($statusCode >= 500) {
            $errorResponse = new Response($res);
            throw (new ServerException(
                $errorResponse['status']['error'] ?? '500 Interval Service Error',
                $statusCode)
            )->setResponse($errorResponse);
        }

        return new Response($res);
    }
}
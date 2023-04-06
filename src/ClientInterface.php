<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant;

use Qdrant\Endpoints\Cluster;
use Qdrant\Endpoints\Collections;
use Qdrant\Endpoints\Service;
use Qdrant\Endpoints\Snapshots;
use Qdrant\Http\HttpClientInterface;

interface ClientInterface extends HttpClientInterface
{
    public function collections(string $collectionName = null): Collections;

    public function snapshots(): Snapshots;

    public function cluster(): Cluster;

    public function service(): Service;
}
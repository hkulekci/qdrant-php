<?php
/**
 * Qdrant PHP Client
 *
 * @link      https://github.com/hkulekci/qdrant-php
 * @license   https://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Qdrant\Tests\Integration;

use GuzzleHttp\Psr7\HttpFactory;
use Qdrant\Config;
use Qdrant\Endpoints\Cluster;
use Qdrant\Endpoints\Collections;
use Qdrant\Endpoints\Service;
use Qdrant\Endpoints\Snapshots;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Http\GuzzleClient;
use Qdrant\Qdrant;

class ClientTest extends AbstractIntegration
{
    public function testClient(): void
    {
        $config = (new Config('http://127.0.0.1'));
        $client = new Qdrant(new GuzzleClient($config));
        $httpFactory = new HttpFactory();
        $request = $httpFactory->createRequest('GET', '/');

        $response = $client->execute($request);
        $this->assertEquals('qdrant - vector search engine', $response['title']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testClientService(): void
    {
        $config = (new Config('http://127.0.0.1'));
        $client = new Qdrant(new GuzzleClient($config));

        $this->assertInstanceOf(Service::class, $client->service());
        $this->assertInstanceOf(Cluster::class, $client->cluster());
        $this->assertInstanceOf(Snapshots::class, $client->snapshots());

        $collections = $client->collections();
        $collections->setCollectionName('sample-collection');
        $this->assertInstanceOf(Collections::class, $collections);
        $this->assertEquals('sample-collection', $collections->getCollectionName());
    }

    public function testClientExecuteRaw(): void
    {
        $config = (new Config('http://127.0.0.1'));
        $client = new Qdrant(new GuzzleClient($config));

        $response = $client->executeRaw('GET', '/');
        $this->assertEquals('qdrant - vector search engine', $response['title']);
    }
}
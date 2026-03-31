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
use Http\Discovery\Psr17FactoryDiscovery;
use Qdrant\Config;
use Qdrant\Endpoints\Cluster;
use Qdrant\Endpoints\Collections;
use Qdrant\Endpoints\Service;
use Qdrant\Endpoints\Snapshots;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Http\Builder;
use Qdrant\Http\GuzzleClient;
use Qdrant\Qdrant;

class ClientTest extends AbstractIntegration
{
    public function testClient(): void
    {
        $qdrantUrl = getenv('QDRANT_URL') ?: '127.0.0.1';
        $qdrantApiKey = getenv('QDRANT_API_KEY') ?: null;

        $config = (new Config($qdrantUrl));
        $config->setApiKey($qdrantApiKey);
        $transform = (new Builder())->build($config);
        $client = new Qdrant($transform);
        $httpFactory = Psr17FactoryDiscovery::findRequestFactory();
        $request = $httpFactory->createRequest('GET', '/');

        $response = $client->execute($request);
        $this->assertEquals('qdrant - vector search engine', $response['title']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testClientService(): void
    {
        $qdrantUrl = getenv('QDRANT_URL') ?: '127.0.0.1';
        $qdrantApiKey = getenv('QDRANT_API_KEY') ?: null;

        $config = (new Config($qdrantUrl));
        $config->setApiKey($qdrantApiKey);
        $transform = (new Builder())->build($config);
        $client = new Qdrant($transform);

        $this->assertInstanceOf(Service::class, $client->service());
        $this->assertInstanceOf(Cluster::class, $client->cluster());
        $this->assertInstanceOf(Snapshots::class, $client->snapshots());

        $collections = $client->collections();
        $collections->setCollectionName('sample-collection');
        $this->assertEquals('sample-collection', $collections->getCollectionName());
    }
}
<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Http;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Qdrant\Config;
use Qdrant\Http\Transport;

class TransportTest extends TestCase
{
    public function testTransportApiKeyHeader(): void
    {
        $client = new Client();
        $config = new Config('127.0.0.1', 3232);
        $config->setApiKey('foo-bar');
        $transport = new Transport($client, $config);

        $httpFactory = Psr17FactoryDiscovery::findRequestFactory();
        $request = $httpFactory->createRequest('GET', '/');

        $this->assertInstanceOf(ResponseInterface::class, $transport->sendRequest($request));
        $lastRequest = $client->getLastRequest();
        $this->assertEquals(['foo-bar'], $lastRequest->getHeader('api-key'));
    }
}
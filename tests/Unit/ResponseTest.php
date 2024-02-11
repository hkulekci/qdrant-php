<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit;

use GuzzleHttp\Psr7\Response as HttpResponse;
use GuzzleHttp\Psr7\Utils;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Exception\ServerException;
use Qdrant\Response;

class ResponseTest extends TestCase
{
    public function testConstructResponse(): void
    {
        $httpFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $httpResponse = $httpFactory->createResponse(200)
            ->withHeader('content-type', 'application/json')
            ->withBody($streamFactory->createStream(json_encode(['foo' => 'bar'])));

        $response = new Response($httpResponse);

        $this->assertEquals('bar', $response['foo']);
    }

    public function testConstructResponse2(): void
    {
        $httpFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $httpResponse = $httpFactory->createResponse(200)
            ->withHeader('content-type', 'text/html')
            ->withBody($streamFactory->createStream(json_encode(['foo' => 'bar'])));

        $response = new Response($httpResponse);

        $this->assertEquals('{"foo":"bar"}', $response['content']);
    }

    public function testConstructResponseWith4xxHttpCode(): void
    {
        $httpFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $httpResponse = $httpFactory->createResponse(418)
            ->withHeader('content-type', 'application/json')
            ->withBody($streamFactory->createStream(json_encode(['foo' => 'bar'])));

        $response = new Response($httpResponse);
        $this->assertEquals('bar', $response['foo']);
    }

    public function testConstructResponseWith5xxHttpCode(): void
    {

        $httpFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $httpResponse = $httpFactory->createResponse(510)
            ->withBody($streamFactory->createStream(json_encode(['foo' => 'bar'])));

        $response = new Response($httpResponse);
        $this->assertEquals('{"foo":"bar"}', $response['content']);
    }
}
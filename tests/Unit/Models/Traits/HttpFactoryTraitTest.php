<?php

namespace Qdrant\Tests\Unit\Models\Traits;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Qdrant\Endpoints\HttpFactoryTrait;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class HttpFactoryTraitTest extends TestCase
{
    public function testCustomHttpRequestFactory()
    {
        $requestFactory = new class implements RequestFactoryInterface {
            public function createRequest(string $method, $uri): RequestInterface
            {
            }
        };

        $mock = new class {
            use HttpFactoryTrait;
        };
        $mock->setHttpFactory($requestFactory);
        $this->assertInstanceOf(get_class($requestFactory), $mock->getHttpFactory());
    }

    public function testHeaders()
    {
        $mock = new class {
            use HttpFactoryTrait;

            public function test(string $method, string $uri, array $body = [], $queryString = []): RequestInterface
            {
                return $this->createRequest($method, $uri . $this->queryBuild($queryString), $body);
            }
        };

        $request = $mock->test('GET', '/api', ['foo' => 'bar'], ['query' => 'string']);

        $this->assertEquals('{"foo":"bar"}', $request->getBody()->getContents());
        $this->assertEquals('query=string', $request->getUri()->getQuery());
        $this->assertEquals('/api', $request->getUri()->getPath());
    }
}

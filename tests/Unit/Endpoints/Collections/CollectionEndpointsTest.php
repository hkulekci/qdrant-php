<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Endpoints\Collections;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Qdrant\ClientInterface;
use Qdrant\Endpoints\Collections;
use Qdrant\Models\Request\CreateVector;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Response;

class CollectionEndpointsTest extends TestCase
{
    public function testCreateNamedVector(): void
    {
        $client = $this->expectRequest(
            'PUT',
            '/collections/test-collection/vectors/image?wait=true',
            ['dense' => ['size' => 4, 'distance' => 'Dot', 'datatype' => 'turbo4']]
        );

        (new Collections($client))->setCollectionName('test-collection')->vectors()->create(
            'image',
            CreateVector::dense(4, VectorParams::DISTANCE_DOT, VectorParams::DATATYPE_TURBO4),
            ['wait' => 'true']
        );
    }

    public function testDeleteNamedVector(): void
    {
        $client = $this->expectRequest('DELETE', '/collections/test-collection/vectors/old%20vector', null);

        (new Collections($client))->setCollectionName('test-collection')->vectors()->delete('old vector');
    }

    public function testOptimizations(): void
    {
        $client = $this->expectRequest(
            'GET',
            '/collections/test-collection/optimizations?with=queued%2Ccompleted&completed_limit=5',
            null
        );

        (new Collections($client))->setCollectionName('test-collection')
            ->optimizations(['with' => 'queued,completed', 'completed_limit' => 5]);
    }

    public function testListShardKeys(): void
    {
        $client = $this->expectRequest('GET', '/collections/test-collection/shards', null);

        (new Collections($client))->setCollectionName('test-collection')->shards()->list();
    }

    private function expectRequest(string $method, string $uri, ?array $body): ClientInterface
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('execute')
            ->with($this->callback(function (RequestInterface $request) use ($method, $uri, $body) {
                $this->assertSame($method, $request->getMethod());
                $this->assertSame($uri, (string)$request->getUri());
                $this->assertSame($body, json_decode((string)$request->getBody(), true));

                return true;
            }))
            ->willReturn($this->createMock(Response::class));

        return $client;
    }
}

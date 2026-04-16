<?php

namespace Qdrant\Tests\Unit\Endpoints\Collections\Points;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Qdrant\ClientInterface;
use Qdrant\Endpoints\Collections\Points\Query;
use Qdrant\Models\Request\Points\BatchQueryRequest;
use Qdrant\Models\Request\Points\QueryGroupsRequest;
use Qdrant\Models\Request\Points\QueryRequest;
use Qdrant\Response;

class QueryTest extends TestCase
{
    private ClientInterface $mockClient;
    private Query $queryEndpoint;

    protected function setUp(): void
    {
        $this->mockClient = $this->createMock(ClientInterface::class);
        $this->queryEndpoint = (new Query($this->mockClient))->setCollectionName('test-collection');
    }

    public function testQuery(): void
    {
        $response = $this->createMock(Response::class);

        $this->mockClient->expects($this->once())
            ->method('execute')
            ->with($this->callback(function (RequestInterface $request) {
                return $request->getMethod() === 'POST'
                    && str_contains((string)$request->getUri(), '/collections/test-collection/points/query')
                    && !str_contains((string)$request->getUri(), '/batch')
                    && !str_contains((string)$request->getUri(), '/groups');
            }))
            ->willReturn($response);

        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setLimit(10);

        $result = $this->queryEndpoint->query($queryRequest);

        $this->assertSame($response, $result);
    }

    public function testQueryWithQueryParams(): void
    {
        $response = $this->createMock(Response::class);

        $this->mockClient->expects($this->once())
            ->method('execute')
            ->with($this->callback(function (RequestInterface $request) {
                return str_contains((string)$request->getUri(), '?timeout=1');
            }))
            ->willReturn($response);

        $queryRequest = new QueryRequest();
        $result = $this->queryEndpoint->query($queryRequest, ['timeout' => 1]);

        $this->assertSame($response, $result);
    }

    public function testBatch(): void
    {
        $response = $this->createMock(Response::class);

        $this->mockClient->expects($this->once())
            ->method('execute')
            ->with($this->callback(function (RequestInterface $request) {
                return $request->getMethod() === 'POST'
                    && str_contains((string)$request->getUri(), '/collections/test-collection/points/query/batch');
            }))
            ->willReturn($response);

        $batchRequest = new BatchQueryRequest([new QueryRequest()]);
        $result = $this->queryEndpoint->batch($batchRequest);

        $this->assertSame($response, $result);
    }

    public function testGroups(): void
    {
        $response = $this->createMock(Response::class);

        $this->mockClient->expects($this->once())
            ->method('execute')
            ->with($this->callback(function (RequestInterface $request) {
                return $request->getMethod() === 'POST'
                    && str_contains((string)$request->getUri(), '/collections/test-collection/points/query/groups');
            }))
            ->willReturn($response);

        $groupsRequest = (new QueryGroupsRequest('category'))
            ->setLimit(10)
            ->setGroupSize(3);

        $result = $this->queryEndpoint->groups($groupsRequest);

        $this->assertSame($response, $result);
    }
}

<?php
/**
 * @since     Mar 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections\Points;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\CreateIndex;
use Qdrant\Models\Request\Points\BatchQueryRequest;
use Qdrant\Models\Request\Points\QueryGroupsRequest;
use Qdrant\Models\Request\Points\QueryRequest;
use Qdrant\Models\VectorStruct;
use Qdrant\Tests\Integration\AbstractIntegration;

class QueryTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createCollections('sample-collection');
        $response = $this->getCollections('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray(self::basicPointDataProvider()));
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('acknowledged', $response['result']['status']);

        $indexResponse = $this->getCollections('sample-collection')
            ->index()->create(new CreateIndex('image', 'keyword'));
        $this->assertEquals('ok', $indexResponse['status']);

        $indexResponse = $this->getCollections('sample-collection')
            ->index()->create(new CreateIndex('category', 'keyword'));
        $this->assertEquals('ok', $indexResponse['status']);
    }

    public static function basicPointDataProvider(): array
    {
        return [
            [
                'id' => 1,
                'vector' => new VectorStruct([1, 3, 400], 'image'),
                'payload' => [
                    'image' => 'sample image',
                    'category' => 'landscape',
                ]
            ],
            [
                'id' => 2,
                'vector' => new VectorStruct([1, 3, 300], 'image'),
                'payload' => [
                    'image' => 'sample image',
                    'category' => 'landscape',
                ]
            ],
            [
                'id' => 3,
                'vector' => new VectorStruct([1, 3, 200], 'image'),
                'payload' => [
                    'image' => 'sample image',
                    'category' => 'portrait',
                ]
            ],
            [
                'id' => 4,
                'vector' => new VectorStruct([1, 3, 100], 'image'),
                'payload' => [
                    'image' => 'another image',
                    'category' => 'portrait',
                ]
            ],
        ];
    }

    public function testQueryWithNearestVector(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setLimit(3);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertNotEmpty($response['result']['points']);
    }

    public function testQueryWithFilter(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('image', 'sample image')
                )
            )
            ->setLimit(3);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertNotEmpty($response['result']['points']);
        $this->assertLessThanOrEqual(3, count($response['result']['points']));
    }

    public function testQueryWithPayload(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setWithPayload(true)
            ->setLimit(3);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertNotEmpty($response['result']['points']);
        $this->assertArrayHasKey('payload', $response['result']['points'][0]);
    }

    public function testQueryWithVector(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setWithVector(true)
            ->setLimit(3);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertNotEmpty($response['result']['points']);
        $this->assertArrayHasKey('vector', $response['result']['points'][0]);
    }

    public function testQueryWithScoreThreshold(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setScoreThreshold(0.99)
            ->setLimit(10);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest);

        $this->assertEquals('ok', $response['status']);

        $queryRequestNoThreshold = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setLimit(10);

        $responseNoThreshold = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequestNoThreshold);

        $this->assertGreaterThanOrEqual(
            count($response['result']['points']),
            count($responseNoThreshold['result']['points'])
        );
    }

    public function testQueryWithOffset(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setLimit(2)
            ->setOffset(0);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest);

        $this->assertEquals('ok', $response['status']);

        $queryRequestOffset = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setLimit(2)
            ->setOffset(2);

        $responseOffset = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequestOffset);

        $this->assertEquals('ok', $responseOffset['status']);
    }

    public function testQueryWithParams(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setParams([
                'hnsw_ef' => 128,
                'exact' => false,
            ])
            ->setLimit(3);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest);

        $this->assertEquals('ok', $response['status']);
    }

    public function testQueryWithUsing(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setLimit(3);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertNotEmpty($response['result']['points']);
    }

    public function testQueryRecommend(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery([
                'recommend' => [
                    'positive' => [1],
                    'negative' => [2],
                ]
            ])
            ->setUsing('image')
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('image', 'sample image')
                )
            )
            ->setLimit(3);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest);

        $this->assertEquals('ok', $response['status']);
    }

    public function testQueryWithQueryParams(): void
    {
        $queryRequest = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setLimit(3);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->query($queryRequest, ['timeout' => 1]);

        $this->assertEquals('ok', $response['status']);
    }

    public function testBatchQuery(): void
    {
        $request1 = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setLimit(3);

        $request2 = (new QueryRequest())
            ->setQuery(['nearest' => [1, 3, 400]])
            ->setUsing('image')
            ->setLimit(2);

        $batchRequest = new BatchQueryRequest([$request1, $request2]);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->batch($batchRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertCount(2, $response['result']);
    }

    public function testBatchQueryWithQueryParams(): void
    {
        $request1 = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setLimit(3);

        $batchRequest = new BatchQueryRequest([$request1]);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->batch($batchRequest, ['timeout' => 1]);

        $this->assertEquals('ok', $response['status']);
    }

    public function testQueryGroups(): void
    {
        $groupsRequest = (new QueryGroupsRequest('category'))
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setGroupSize(2)
            ->setLimit(5);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->groups($groupsRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('groups', $response['result']);
    }

    public function testQueryGroupsWithPayload(): void
    {
        $groupsRequest = (new QueryGroupsRequest('category'))
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setGroupSize(2)
            ->setLimit(5)
            ->setWithPayload(true);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->groups($groupsRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('groups', $response['result']);
        $this->assertNotEmpty($response['result']['groups']);
    }

    public function testQueryGroupsWithFilter(): void
    {
        $groupsRequest = (new QueryGroupsRequest('category'))
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('image', 'sample image')
                )
            )
            ->setGroupSize(2)
            ->setLimit(5);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->groups($groupsRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('groups', $response['result']);
    }

    public function testQueryGroupsWithQueryParams(): void
    {
        $groupsRequest = (new QueryGroupsRequest('category'))
            ->setQuery(['nearest' => [1, 2, 300]])
            ->setUsing('image')
            ->setGroupSize(2)
            ->setLimit(5);

        $response = $this->getCollections('sample-collection')
            ->points()->query()->groups($groupsRequest, ['timeout' => 1]);

        $this->assertEquals('ok', $response['status']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);

        $collections->setCollectionName('sample-collection')->delete();
    }
}

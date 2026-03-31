<?php

namespace Qdrant\Tests\Unit\Models\Request\Points;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\Points\BatchQueryRequest;
use Qdrant\Models\Request\Points\QueryRequest;

class BatchQueryRequestTest extends TestCase
{
    public function testBatchQueryRequestWithMultipleSearches(): void
    {
        $request1 = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setLimit(10);

        $request2 = (new QueryRequest())
            ->setQuery(['nearest' => [0.4, 0.5, 0.6]])
            ->setLimit(5);

        $batch = new BatchQueryRequest([$request1, $request2]);

        $result = $batch->toArray();

        $this->assertArrayHasKey('searches', $result);
        $this->assertCount(2, $result['searches']);
        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $result['searches'][0]['query']);
        $this->assertEquals(10, $result['searches'][0]['limit']);
        $this->assertEquals(['nearest' => [0.4, 0.5, 0.6]], $result['searches'][1]['query']);
        $this->assertEquals(5, $result['searches'][1]['limit']);
    }

    public function testBatchQueryRequestWithEmptyArray(): void
    {
        $batch = new BatchQueryRequest([]);

        $result = $batch->toArray();

        $this->assertArrayHasKey('searches', $result);
        $this->assertCount(0, $result['searches']);
    }

    public function testAddSearch(): void
    {
        $batch = new BatchQueryRequest([]);

        $batch->addSearch(
            (new QueryRequest())
                ->setQuery(['nearest' => [0.1, 0.2]])
                ->setLimit(10)
        );

        $result = $batch->toArray();

        $this->assertCount(1, $result['searches']);
        $this->assertEquals(['nearest' => [0.1, 0.2]], $result['searches'][0]['query']);
    }

    public function testAddSearchReturnsSelf(): void
    {
        $batch = new BatchQueryRequest([]);

        $returned = $batch->addSearch(
            (new QueryRequest())->setQuery(['nearest' => [1, 2, 3]])
        );

        $this->assertSame($batch, $returned);
    }

    public function testAddMultipleSearchesChained(): void
    {
        $batch = new BatchQueryRequest([]);

        $batch
            ->addSearch((new QueryRequest())->setQuery(['nearest' => [1, 2, 3]])->setLimit(5))
            ->addSearch((new QueryRequest())->setQuery(['nearest' => [4, 5, 6]])->setLimit(10))
            ->addSearch((new QueryRequest())->setQuery(['nearest' => [7, 8, 9]])->setLimit(3));

        $result = $batch->toArray();

        $this->assertCount(3, $result['searches']);
    }

    public function testConstructorAndAddSearchCombined(): void
    {
        $request1 = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setLimit(10);

        $batch = new BatchQueryRequest([$request1]);

        $batch->addSearch(
            (new QueryRequest())
                ->setQuery(['nearest' => [0.4, 0.5, 0.6]])
                ->setLimit(5)
        );

        $result = $batch->toArray();

        $this->assertCount(2, $result['searches']);
    }

    public function testSearchesWithDifferentQueryTypes(): void
    {
        $nearestRequest = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setUsing('image')
            ->setLimit(10);

        $recommendRequest = (new QueryRequest())
            ->setQuery(['recommend' => ['positive' => [1], 'negative' => [2]]])
            ->setUsing('image')
            ->setLimit(5);

        $fusionRequest = (new QueryRequest())
            ->setQuery(['fusion' => 'rrf'])
            ->setLimit(3);

        $batch = new BatchQueryRequest([$nearestRequest, $recommendRequest, $fusionRequest]);

        $result = $batch->toArray();

        $this->assertCount(3, $result['searches']);
        $this->assertArrayHasKey('nearest', $result['searches'][0]['query']);
        $this->assertEquals('image', $result['searches'][0]['using']);
        $this->assertArrayHasKey('recommend', $result['searches'][1]['query']);
        $this->assertArrayHasKey('fusion', $result['searches'][2]['query']);
    }

    public function testSearchesWithFilters(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setFilter(
                (new Filter())->addMust(new MatchString('color', 'red'))
            )
            ->setLimit(10);

        $batch = new BatchQueryRequest([$request]);

        $result = $batch->toArray();

        $this->assertCount(1, $result['searches']);
        $this->assertArrayHasKey('filter', $result['searches'][0]);
    }

    public function testSearchesWithAllOptions(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setUsing('image')
            ->setFilter((new Filter())->addMust(new MatchString('color', 'red')))
            ->setParams(['hnsw_ef' => 128, 'exact' => false])
            ->setScoreThreshold(0.5)
            ->setLimit(10)
            ->setOffset(5)
            ->setWithPayload(true)
            ->setWithVector(true)
            ->setLookupFrom(['collection' => 'other', 'vector' => 'name']);

        $batch = new BatchQueryRequest([$request]);

        $result = $batch->toArray();

        $search = $result['searches'][0];
        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $search['query']);
        $this->assertEquals('image', $search['using']);
        $this->assertArrayHasKey('filter', $search);
        $this->assertEquals(['hnsw_ef' => 128, 'exact' => false], $search['params']);
        $this->assertEquals(0.5, $search['score_threshold']);
        $this->assertEquals(10, $search['limit']);
        $this->assertEquals(5, $search['offset']);
        $this->assertTrue($search['with_payload']);
        $this->assertTrue($search['with_vector']);
        $this->assertEquals(['collection' => 'other', 'vector' => 'name'], $search['lookup_from']);
    }

    public function testGetSearchesViaPropertyAccessor(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 3]])
            ->setLimit(5);

        $batch = new BatchQueryRequest([$request]);

        $searches = $batch->getSearches();

        $this->assertCount(1, $searches);
        $this->assertInstanceOf(QueryRequest::class, $searches[0]);
    }

    public function testConstructorTypeSafety(): void
    {
        $this->expectException(\TypeError::class);

        $batch = new BatchQueryRequest(['not a QueryRequest']);
    }

    public function testSingleSearch(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 3]])
            ->setLimit(1);

        $batch = new BatchQueryRequest([$request]);

        $result = $batch->toArray();

        $this->assertCount(1, $result['searches']);
        $this->assertEquals(1, $result['searches'][0]['limit']);
    }
}

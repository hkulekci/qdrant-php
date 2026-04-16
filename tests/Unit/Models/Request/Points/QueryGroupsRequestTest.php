<?php

namespace Qdrant\Tests\Unit\Models\Request\Points;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\Points\QueryGroupsRequest;

class QueryGroupsRequestTest extends TestCase
{
    public function testBasicGroupBy(): void
    {
        $request = new QueryGroupsRequest('category');

        $result = $request->toArray();

        $this->assertEquals(['group_by' => 'category'], $result);
    }

    public function testGroupByIsAlwaysPresent(): void
    {
        $request = (new QueryGroupsRequest('color'))
            ->setLimit(5);

        $result = $request->toArray();

        $this->assertArrayHasKey('group_by', $result);
        $this->assertEquals('color', $result['group_by']);
    }

    public function testSetQuery(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]]);

        $result = $request->toArray();

        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $result['query']);
    }

    public function testSetQueryWithRecommend(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setQuery(['recommend' => ['positive' => [1], 'negative' => [2]]]);

        $result = $request->toArray();

        $this->assertEquals(
            ['recommend' => ['positive' => [1], 'negative' => [2]]],
            $result['query']
        );
    }

    public function testSetQueryWithFusion(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setQuery(['fusion' => 'rrf']);

        $result = $request->toArray();

        $this->assertEquals(['fusion' => 'rrf'], $result['query']);
    }

    public function testSetQueryWithOrderBy(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setQuery(['order_by' => 'price']);

        $result = $request->toArray();

        $this->assertEquals(['order_by' => 'price'], $result['query']);
    }

    public function testSetUsing(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setQuery(['nearest' => [1, 2, 3]])
            ->setUsing('image');

        $result = $request->toArray();

        $this->assertEquals('image', $result['using']);
    }

    public function testSetFilter(): void
    {
        $filter = (new Filter())->addMust(new MatchString('city', 'Berlin'));

        $request = (new QueryGroupsRequest('category'))
            ->setFilter($filter);

        $result = $request->toArray();

        $this->assertArrayHasKey('filter', $result);
        $this->assertArrayHasKey('must', $result['filter']);
    }

    public function testEmptyFilterIsExcluded(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setFilter(new Filter());

        $result = $request->toArray();

        $this->assertArrayNotHasKey('filter', $result);
    }

    public function testSetParams(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setParams(['hnsw_ef' => 128, 'exact' => false]);

        $result = $request->toArray();

        $this->assertEquals(['hnsw_ef' => 128, 'exact' => false], $result['params']);
    }

    public function testEmptyParamsAreExcluded(): void
    {
        $request = new QueryGroupsRequest('category');

        $result = $request->toArray();

        $this->assertArrayNotHasKey('params', $result);
    }

    public function testSetScoreThreshold(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setScoreThreshold(0.75);

        $result = $request->toArray();

        $this->assertEquals(0.75, $result['score_threshold']);
    }

    public function testSetScoreThresholdZero(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setScoreThreshold(0.0);

        $result = $request->toArray();

        $this->assertArrayHasKey('score_threshold', $result);
        $this->assertEquals(0.0, $result['score_threshold']);
    }

    public function testSetGroupSize(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setGroupSize(5);

        $result = $request->toArray();

        $this->assertEquals(5, $result['group_size']);
    }

    public function testSetLimit(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setLimit(10);

        $result = $request->toArray();

        $this->assertEquals(10, $result['limit']);
    }

    public function testSetWithPayloadTrue(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setWithPayload(true);

        $result = $request->toArray();

        $this->assertTrue($result['with_payload']);
    }

    public function testSetWithPayloadFalse(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setWithPayload(false);

        $result = $request->toArray();

        $this->assertArrayHasKey('with_payload', $result);
        $this->assertFalse($result['with_payload']);
    }

    public function testSetWithPayloadArray(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setWithPayload(['title', 'description']);

        $result = $request->toArray();

        $this->assertEquals(['title', 'description'], $result['with_payload']);
    }

    public function testSetWithVectorTrue(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setWithVector(true);

        $result = $request->toArray();

        $this->assertTrue($result['with_vector']);
    }

    public function testSetWithVectorArray(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setWithVector(['image']);

        $result = $request->toArray();

        $this->assertEquals(['image'], $result['with_vector']);
    }

    public function testSetShardKeyString(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setShardKey('shard_1');

        $result = $request->toArray();

        $this->assertEquals('shard_1', $result['shard_key']);
    }

    public function testSetShardKeyArray(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setShardKey(['shard_1', 'shard_2']);

        $result = $request->toArray();

        $this->assertEquals(['shard_1', 'shard_2'], $result['shard_key']);
    }

    public function testSetPrefetch(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setPrefetch([
                ['query' => ['nearest' => [1, 2, 3]], 'limit' => 100],
            ]);

        $result = $request->toArray();

        $this->assertCount(1, $result['prefetch']);
    }

    public function testSetLookupFrom(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setLookupFrom(['collection' => 'other', 'vector' => 'name']);

        $result = $request->toArray();

        $this->assertEquals(
            ['collection' => 'other', 'vector' => 'name'],
            $result['lookup_from']
        );
    }

    public function testSetWithLookupString(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setWithLookup('other_collection');

        $result = $request->toArray();

        $this->assertEquals('other_collection', $result['with_lookup']);
    }

    public function testSetWithLookupArray(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setWithLookup([
                'collection' => 'other_collection',
                'with_payload' => ['title'],
            ]);

        $result = $request->toArray();

        $this->assertEquals(
            ['collection' => 'other_collection', 'with_payload' => ['title']],
            $result['with_lookup']
        );
    }

    public function testAllOptionsToArray(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setShardKey('shard_1')
            ->setPrefetch([['query' => ['nearest' => [1, 2, 3]], 'limit' => 100]])
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setUsing('image')
            ->setFilter((new Filter())->addMust(new MatchString('city', 'Berlin')))
            ->setParams(['hnsw_ef' => 128])
            ->setScoreThreshold(0.5)
            ->setGroupSize(5)
            ->setLimit(10)
            ->setWithPayload(true)
            ->setWithVector(true)
            ->setLookupFrom(['collection' => 'other', 'vector' => 'name'])
            ->setWithLookup('lookup_collection');

        $result = $request->toArray();

        $this->assertEquals('category', $result['group_by']);
        $this->assertEquals('shard_1', $result['shard_key']);
        $this->assertCount(1, $result['prefetch']);
        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $result['query']);
        $this->assertEquals('image', $result['using']);
        $this->assertArrayHasKey('filter', $result);
        $this->assertEquals(['hnsw_ef' => 128], $result['params']);
        $this->assertEquals(0.5, $result['score_threshold']);
        $this->assertEquals(5, $result['group_size']);
        $this->assertEquals(10, $result['limit']);
        $this->assertTrue($result['with_payload']);
        $this->assertTrue($result['with_vector']);
        $this->assertEquals(['collection' => 'other', 'vector' => 'name'], $result['lookup_from']);
        $this->assertEquals('lookup_collection', $result['with_lookup']);
    }

    public function testNullValuesAreExcluded(): void
    {
        $request = new QueryGroupsRequest('category');

        $result = $request->toArray();

        $this->assertArrayNotHasKey('shard_key', $result);
        $this->assertArrayNotHasKey('prefetch', $result);
        $this->assertArrayNotHasKey('query', $result);
        $this->assertArrayNotHasKey('using', $result);
        $this->assertArrayNotHasKey('filter', $result);
        $this->assertArrayNotHasKey('params', $result);
        $this->assertArrayNotHasKey('score_threshold', $result);
        $this->assertArrayNotHasKey('group_size', $result);
        $this->assertArrayNotHasKey('limit', $result);
        $this->assertArrayNotHasKey('with_vector', $result);
        $this->assertArrayNotHasKey('with_payload', $result);
        $this->assertArrayNotHasKey('lookup_from', $result);
        $this->assertArrayNotHasKey('with_lookup', $result);
    }

    public function testFluentInterface(): void
    {
        $request = new QueryGroupsRequest('category');

        $this->assertSame($request, $request->setQuery(['nearest' => [1, 2, 3]]));
        $this->assertSame($request, $request->setUsing('image'));
        $this->assertSame($request, $request->setLimit(10));
        $this->assertSame($request, $request->setGroupSize(5));
        $this->assertSame($request, $request->setScoreThreshold(0.5));
        $this->assertSame($request, $request->setWithPayload(true));
        $this->assertSame($request, $request->setWithVector(true));
        $this->assertSame($request, $request->setShardKey('shard'));
        $this->assertSame($request, $request->setPrefetch([]));
        $this->assertSame($request, $request->setParams([]));
        $this->assertSame($request, $request->setFilter(new Filter()));
        $this->assertSame($request, $request->setLookupFrom([]));
        $this->assertSame($request, $request->setWithLookup('col'));
    }

    public function testPropertyAccessor(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setQuery(['nearest' => [1, 2, 3]])
            ->setGroupSize(5);

        $this->assertEquals('category', $request->getGroupBy());
        $this->assertEquals(['nearest' => [1, 2, 3]], $request->getQuery());
        $this->assertEquals(5, $request->getGroupSize());
    }

    public function testSetWithVectorFalse(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setWithVector(false);

        $result = $request->toArray();

        $this->assertArrayHasKey('with_vector', $result);
        $this->assertFalse($result['with_vector']);
    }

    public function testPropertyAccessorForAllProperties(): void
    {
        $filter = (new Filter())->addMust(new MatchString('city', 'Berlin'));
        $request = (new QueryGroupsRequest('category'))
            ->setShardKey('shard_1')
            ->setPrefetch([['query' => ['nearest' => [1, 2, 3]], 'limit' => 100]])
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setUsing('image')
            ->setFilter($filter)
            ->setParams(['hnsw_ef' => 128])
            ->setScoreThreshold(0.5)
            ->setGroupSize(5)
            ->setLimit(10)
            ->setWithPayload(true)
            ->setWithVector(true)
            ->setLookupFrom(['collection' => 'other', 'vector' => 'name'])
            ->setWithLookup('lookup_collection');

        $this->assertEquals('shard_1', $request->getShardKey());
        $this->assertCount(1, $request->getPrefetch());
        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $request->getQuery());
        $this->assertEquals('image', $request->getUsing());
        $this->assertSame($filter, $request->getFilter());
        $this->assertEquals(['hnsw_ef' => 128], $request->getParams());
        $this->assertEquals(0.5, $request->getScoreThreshold());
        $this->assertEquals(5, $request->getGroupSize());
        $this->assertEquals(10, $request->getLimit());
        $this->assertTrue($request->getWithPayload());
        $this->assertTrue($request->getWithVector());
        $this->assertEquals(['collection' => 'other', 'vector' => 'name'], $request->getLookupFrom());
        $this->assertEquals('lookup_collection', $request->getWithLookup());
    }
}

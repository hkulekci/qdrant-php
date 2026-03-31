<?php

namespace Qdrant\Tests\Unit\Models\Request\Points;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\Points\QueryRequest;

class QueryRequestTest extends TestCase
{
    public function testEmptyQueryRequest(): void
    {
        $request = new QueryRequest();

        $this->assertEquals([], $request->toArray());
    }

    // -- Query types --

    public function testQueryWithNearestVector(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setLimit(10);

        $result = $request->toArray();

        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $result['query']);
        $this->assertEquals(10, $result['limit']);
    }

    public function testQueryWithRecommend(): void
    {
        $request = (new QueryRequest())
            ->setQuery([
                'recommend' => [
                    'positive' => [1, 2, 3],
                    'negative' => [4],
                    'strategy' => 'average_vector',
                ]
            ])
            ->setLimit(5);

        $result = $request->toArray();

        $this->assertEquals([1, 2, 3], $result['query']['recommend']['positive']);
        $this->assertEquals([4], $result['query']['recommend']['negative']);
        $this->assertEquals('average_vector', $result['query']['recommend']['strategy']);
        $this->assertEquals(5, $result['limit']);
    }

    public function testQueryWithDiscover(): void
    {
        $request = (new QueryRequest())
            ->setQuery([
                'discover' => [
                    'target' => [0.1, 0.2, 0.3],
                    'context' => [
                        ['positive' => 1, 'negative' => 2],
                    ],
                ]
            ])
            ->setLimit(5);

        $result = $request->toArray();

        $this->assertArrayHasKey('discover', $result['query']);
        $this->assertEquals([0.1, 0.2, 0.3], $result['query']['discover']['target']);
    }

    public function testQueryWithFusion(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['fusion' => 'rrf'])
            ->setLimit(10);

        $result = $request->toArray();

        $this->assertEquals(['fusion' => 'rrf'], $result['query']);
    }

    public function testQueryWithOrderBy(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['order_by' => 'timestamp'])
            ->setLimit(10);

        $result = $request->toArray();

        $this->assertEquals(['order_by' => 'timestamp'], $result['query']);
    }

    public function testQueryWithSample(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['sample' => 'random'])
            ->setLimit(10);

        $result = $request->toArray();

        $this->assertEquals(['sample' => 'random'], $result['query']);
    }

    // -- Prefetch --

    public function testPrefetch(): void
    {
        $request = (new QueryRequest())
            ->setPrefetch([
                ['query' => ['nearest' => [0.1, 0.2]], 'using' => 'dense', 'limit' => 100],
                ['query' => ['nearest' => [0.3, 0.4]], 'using' => 'sparse', 'limit' => 100],
            ])
            ->setQuery(['fusion' => 'rrf'])
            ->setLimit(10);

        $result = $request->toArray();

        $this->assertCount(2, $result['prefetch']);
        $this->assertEquals('dense', $result['prefetch'][0]['using']);
        $this->assertEquals('sparse', $result['prefetch'][1]['using']);
    }

    public function testPrefetchNotIncludedWhenNull(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 3]]);

        $this->assertArrayNotHasKey('prefetch', $request->toArray());
    }

    // -- Using --

    public function testUsing(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 3]])
            ->setUsing('image');

        $result = $request->toArray();

        $this->assertEquals('image', $result['using']);
    }

    public function testUsingNotIncludedWhenNull(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 3]]);

        $this->assertArrayNotHasKey('using', $request->toArray());
    }

    // -- Filter --

    public function testFilter(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('city', 'Berlin')
                )
            );

        $result = $request->toArray();

        $this->assertEquals(
            ['must' => [['key' => 'city', 'match' => ['value' => 'Berlin']]]],
            $result['filter']
        );
    }

    public function testEmptyFilterIsExcluded(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 3]])
            ->setFilter(new Filter());

        $this->assertArrayNotHasKey('filter', $request->toArray());
    }

    // -- Params --

    public function testParams(): void
    {
        $request = (new QueryRequest())
            ->setParams(['hnsw_ef' => 128, 'exact' => false]);

        $result = $request->toArray();

        $this->assertEquals(['hnsw_ef' => 128, 'exact' => false], $result['params']);
    }

    public function testEmptyParamsAreExcluded(): void
    {
        $request = new QueryRequest();

        $this->assertArrayNotHasKey('params', $request->toArray());
    }

    // -- Score threshold --

    public function testScoreThreshold(): void
    {
        $request = (new QueryRequest())
            ->setScoreThreshold(0.75);

        $result = $request->toArray();

        $this->assertEquals(0.75, $result['score_threshold']);
    }

    public function testScoreThresholdZero(): void
    {
        $request = (new QueryRequest())
            ->setScoreThreshold(0.0);

        $result = $request->toArray();

        $this->assertArrayHasKey('score_threshold', $result);
        $this->assertEquals(0.0, $result['score_threshold']);
    }

    public function testScoreThresholdNotIncludedWhenNull(): void
    {
        $request = new QueryRequest();

        $this->assertArrayNotHasKey('score_threshold', $request->toArray());
    }

    // -- Limit --

    public function testLimit(): void
    {
        $request = (new QueryRequest())->setLimit(10);

        $this->assertEquals(10, $request->toArray()['limit']);
    }

    public function testLimitNotIncludedWhenNull(): void
    {
        $request = new QueryRequest();

        $this->assertArrayNotHasKey('limit', $request->toArray());
    }

    // -- Offset --

    public function testOffset(): void
    {
        $request = (new QueryRequest())->setOffset(5);

        $this->assertEquals(5, $request->toArray()['offset']);
    }

    public function testOffsetZero(): void
    {
        $request = (new QueryRequest())->setOffset(0);

        $result = $request->toArray();

        $this->assertArrayHasKey('offset', $result);
        $this->assertEquals(0, $result['offset']);
    }

    public function testOffsetNotIncludedWhenNull(): void
    {
        $request = new QueryRequest();

        $this->assertArrayNotHasKey('offset', $request->toArray());
    }

    // -- With payload --

    public function testWithPayloadTrue(): void
    {
        $request = (new QueryRequest())->setWithPayload(true);

        $this->assertTrue($request->toArray()['with_payload']);
    }

    public function testWithPayloadFalse(): void
    {
        $request = (new QueryRequest())->setWithPayload(false);

        $result = $request->toArray();

        $this->assertArrayHasKey('with_payload', $result);
        $this->assertFalse($result['with_payload']);
    }

    public function testWithPayloadArray(): void
    {
        $request = (new QueryRequest())->setWithPayload(['title', 'description']);

        $this->assertEquals(['title', 'description'], $request->toArray()['with_payload']);
    }

    public function testWithPayloadNotIncludedWhenNull(): void
    {
        $request = new QueryRequest();

        $this->assertArrayNotHasKey('with_payload', $request->toArray());
    }

    // -- With vector --

    public function testWithVectorTrue(): void
    {
        $request = (new QueryRequest())->setWithVector(true);

        $this->assertTrue($request->toArray()['with_vector']);
    }

    public function testWithVectorFalse(): void
    {
        $request = (new QueryRequest())->setWithVector(false);

        $result = $request->toArray();

        $this->assertArrayHasKey('with_vector', $result);
        $this->assertFalse($result['with_vector']);
    }

    public function testWithVectorArray(): void
    {
        $request = (new QueryRequest())->setWithVector(['image', 'text']);

        $this->assertEquals(['image', 'text'], $request->toArray()['with_vector']);
    }

    public function testWithVectorNotIncludedWhenNull(): void
    {
        $request = new QueryRequest();

        $this->assertArrayNotHasKey('with_vector', $request->toArray());
    }

    // -- Shard key --

    public function testShardKeyString(): void
    {
        $request = (new QueryRequest())->setShardKey('shard_1');

        $this->assertEquals('shard_1', $request->toArray()['shard_key']);
    }

    public function testShardKeyArray(): void
    {
        $request = (new QueryRequest())->setShardKey(['shard_1', 'shard_2']);

        $this->assertEquals(['shard_1', 'shard_2'], $request->toArray()['shard_key']);
    }

    public function testShardKeyNotIncludedWhenNull(): void
    {
        $request = new QueryRequest();

        $this->assertArrayNotHasKey('shard_key', $request->toArray());
    }

    // -- Lookup from --

    public function testLookupFrom(): void
    {
        $request = (new QueryRequest())
            ->setLookupFrom(['collection' => 'other', 'vector' => 'name']);

        $result = $request->toArray();

        $this->assertEquals(
            ['collection' => 'other', 'vector' => 'name'],
            $result['lookup_from']
        );
    }

    public function testLookupFromNotIncludedWhenNull(): void
    {
        $request = new QueryRequest();

        $this->assertArrayNotHasKey('lookup_from', $request->toArray());
    }

    // -- All options combined --

    public function testAllOptions(): void
    {
        $request = (new QueryRequest())
            ->setShardKey('shard_1')
            ->setPrefetch([['query' => ['nearest' => [1, 2, 3]], 'limit' => 100]])
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setUsing('image')
            ->setFilter((new Filter())->addMust(new MatchString('city', 'Berlin')))
            ->setParams(['hnsw_ef' => 128, 'exact' => false])
            ->setScoreThreshold(0.5)
            ->setLimit(10)
            ->setOffset(5)
            ->setWithPayload(true)
            ->setWithVector(['image'])
            ->setLookupFrom(['collection' => 'other_collection']);

        $result = $request->toArray();

        $this->assertEquals('shard_1', $result['shard_key']);
        $this->assertCount(1, $result['prefetch']);
        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $result['query']);
        $this->assertEquals('image', $result['using']);
        $this->assertArrayHasKey('filter', $result);
        $this->assertEquals(['hnsw_ef' => 128, 'exact' => false], $result['params']);
        $this->assertEquals(0.5, $result['score_threshold']);
        $this->assertEquals(10, $result['limit']);
        $this->assertEquals(5, $result['offset']);
        $this->assertTrue($result['with_payload']);
        $this->assertEquals(['image'], $result['with_vector']);
        $this->assertEquals(['collection' => 'other_collection'], $result['lookup_from']);
    }

    // -- Null values excluded --

    public function testNullValuesAreExcluded(): void
    {
        $request = new QueryRequest();

        $result = $request->toArray();

        $this->assertArrayNotHasKey('shard_key', $result);
        $this->assertArrayNotHasKey('prefetch', $result);
        $this->assertArrayNotHasKey('query', $result);
        $this->assertArrayNotHasKey('using', $result);
        $this->assertArrayNotHasKey('filter', $result);
        $this->assertArrayNotHasKey('params', $result);
        $this->assertArrayNotHasKey('score_threshold', $result);
        $this->assertArrayNotHasKey('limit', $result);
        $this->assertArrayNotHasKey('offset', $result);
        $this->assertArrayNotHasKey('with_vector', $result);
        $this->assertArrayNotHasKey('with_payload', $result);
        $this->assertArrayNotHasKey('lookup_from', $result);
    }

    // -- Fluent interface --

    public function testFluentInterface(): void
    {
        $request = new QueryRequest();

        $this->assertSame($request, $request->setQuery(['nearest' => [1, 2, 3]]));
        $this->assertSame($request, $request->setUsing('image'));
        $this->assertSame($request, $request->setLimit(10));
        $this->assertSame($request, $request->setOffset(0));
        $this->assertSame($request, $request->setScoreThreshold(0.5));
        $this->assertSame($request, $request->setWithPayload(true));
        $this->assertSame($request, $request->setWithVector(true));
        $this->assertSame($request, $request->setShardKey('shard'));
        $this->assertSame($request, $request->setPrefetch([]));
        $this->assertSame($request, $request->setParams([]));
        $this->assertSame($request, $request->setFilter(new Filter()));
        $this->assertSame($request, $request->setLookupFrom([]));
    }

    // -- Property accessor --

    public function testPropertyAccessor(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [1, 2, 3]])
            ->setUsing('image')
            ->setLimit(10);

        $this->assertEquals(['nearest' => [1, 2, 3]], $request->getQuery());
        $this->assertEquals('image', $request->getUsing());
        $this->assertEquals(10, $request->getLimit());
    }

    public function testPropertyAccessorForNullValues(): void
    {
        $request = new QueryRequest();

        $this->assertNull($request->getQuery());
        $this->assertNull($request->getUsing());
        $this->assertNull($request->getLimit());
        $this->assertNull($request->getScoreThreshold());
        $this->assertNull($request->getOffset());
    }

    public function testPropertyAccessorThrowsForNonExistentProperty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new QueryRequest();
        $request->getNonExistent();
    }
}
